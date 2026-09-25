import os
import time
import unittest
from pathlib import Path

from selenium import webdriver
from selenium.common.exceptions import TimeoutException, WebDriverException
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select, WebDriverWait


BASE_URL = os.getenv("E2E_BASE_URL", "http://localhost/opi_eestis").rstrip("/")
BROWSER = os.getenv("E2E_BROWSER", "edge").lower()
HEADLESS = os.getenv("E2E_HEADLESS", "0") == "1"
ADMIN_EMAIL = os.getenv("E2E_ADMIN_EMAIL", "")
ADMIN_PASSWORD = os.getenv("E2E_ADMIN_PASSWORD", "")
SCREENSHOT_DIR = Path(__file__).parent / "screenshots"
SCREENSHOT_DIR.mkdir(parents=True, exist_ok=True)


def create_driver():
    """Create a browser through Selenium Manager; no manual driver path is needed."""
    if BROWSER == "chrome":
        options = webdriver.ChromeOptions()
        if HEADLESS:
            options.add_argument("--headless=new")
        options.add_argument("--window-size=1440,1000")
        return webdriver.Chrome(options=options)

    options = webdriver.EdgeOptions()
    if HEADLESS:
        options.add_argument("--headless=new")
    options.add_argument("--window-size=1440,1000")
    return webdriver.Edge(options=options)


class OpiEestisE2E(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        try:
            cls.driver = create_driver()
        except WebDriverException as exc:
            raise unittest.SkipTest(
                "Brauserit ei saanud käivitada. Kontrolli, et Edge/Chrome on paigaldatud "
                "ja Seleniumi pakett on installitud."
            ) from exc
        cls.wait = WebDriverWait(cls.driver, 10)
        cls.driver.set_window_size(1440, 1000)

    @classmethod
    def tearDownClass(cls):
        if hasattr(cls, "driver"):
            cls.driver.quit()

    def setUp(self):
        self.driver.delete_all_cookies()

    def tearDown(self):
        # Save a screenshot only when the test failed.
        outcome = getattr(self, "_outcome", None)
        failed = False
        if outcome and getattr(outcome, "result", None):
            failed = any(test is self and err for test, err in outcome.result.failures + outcome.result.errors)
        if failed:
            stamp = time.strftime("%Y%m%d-%H%M%S")
            self.driver.save_screenshot(str(SCREENSHOT_DIR / f"FAIL_{self._testMethodName}_{stamp}.png"))

    def open(self, path=""):
        self.driver.get(f"{BASE_URL}/{path.lstrip('/')}")

    def wait_for_text(self, text):
        return self.wait.until(EC.presence_of_element_located((By.XPATH, f"//*[contains(normalize-space(.), {self.xpath_literal(text)})]")))

    @staticmethod
    def xpath_literal(value):
        if "'" not in value:
            return f"'{value}'"
        if '"' not in value:
            return f'"{value}"'
        parts = value.split("'")
        return "concat(" + ", \"'\", ".join(f"'{p}'" for p in parts) + ")"

    def login_admin(self):
        if not ADMIN_EMAIL or not ADMIN_PASSWORD:
            self.skipTest("Admini E2E jaoks määra E2E_ADMIN_EMAIL ja E2E_ADMIN_PASSWORD.")
        self.open("admin/")
        self.wait.until(EC.visibility_of_element_located((By.ID, "email"))).send_keys(ADMIN_EMAIL)
        self.driver.find_element(By.ID, "password").send_keys(ADMIN_PASSWORD)
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        self.wait.until(EC.url_contains("/admin/"))
        self.wait_for_text("Õppematerjalide haldamine")

    def test_01_homepage_and_main_navigation(self):
        self.open()
        self.assertIn("ÕpiEestis", self.driver.title)
        self.wait_for_text("TOP 3 ÕPPEMATERJALID")
        nav = self.wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, "nav.np-nav")))
        self.assertIn("Õppeained", nav.text)
        self.assertIn("Kõik õppematerjalid", nav.text)
        self.assertIn("Avaleht", nav.text)

    def test_02_lesson_search_and_subject_filter(self):
        self.open("lessons")
        search = self.wait.until(EC.visibility_of_element_located((By.NAME, "q")))
        search.send_keys("Present simple")
        self.driver.find_element(By.CSS_SELECTOR, "form.filter-bar button[type='submit']").click()
        self.wait_for_text("Present simple: daily routines")
        cards = self.driver.find_elements(By.CSS_SELECTOR, ".lesson-card")
        self.assertGreaterEqual(len(cards), 1)

        self.open("lessons")
        subject_select = Select(self.wait.until(EC.visibility_of_element_located((By.NAME, "subject"))))
        subject_select.select_by_visible_text("Matemaatika")
        self.driver.find_element(By.CSS_SELECTOR, "form.filter-bar button[type='submit']").click()
        self.wait_for_text("Murrud ja nende liitmine")
        for meta in self.driver.find_elements(By.CSS_SELECTOR, ".lesson-card .card-meta"):
            self.assertIn("Matemaatika", meta.text)

    def test_03_lesson_and_quiz_end_to_end(self):
        self.open("lessons?q=Present+simple")
        lesson_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Present simple: daily routines")))
        lesson_link.click()
        self.wait_for_text("ÕPPETUNNI INFO")
        self.wait_for_text("Arutelu")

        quiz_link = self.wait.until(EC.element_to_be_clickable((By.PARTIAL_LINK_TEXT, "Alusta testi")))
        quiz_link.click()
        questions = self.wait.until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, "fieldset.question-card")))
        self.assertGreaterEqual(len(questions), 1)

        # Choose one answer in every question to exercise the full browser -> PHP -> DB -> result UI flow.
        for question in questions:
            question.find_element(By.CSS_SELECTOR, "input[type='radio']").click()
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        self.wait_for_text("Sinu tulemus:")
        self.wait_for_text("Vastuste ülevaade")
        self.assertGreaterEqual(len(self.driver.find_elements(By.CSS_SELECTOR, ".feedback-item")), 1)

    def test_04_registration_and_login_ui(self):
        self.open("register")
        self.wait_for_text("Loo konto")
        for element_id in ("name", "email", "password", "confirm"):
            self.assertTrue(self.driver.find_element(By.ID, element_id).is_displayed())
        self.assertEqual("8", self.driver.find_element(By.ID, "password").get_attribute("minlength"))

        self.open("admin/")
        self.wait_for_text("Logi sisse")
        self.assertTrue(self.driver.find_element(By.ID, "email").is_displayed())
        self.assertTrue(self.driver.find_element(By.ID, "password").is_displayed())

    def test_05_admin_login_and_management_ui(self):
        self.login_admin()
        self.assertTrue(self.driver.find_element(By.LINK_TEXT, "Halduspaneel").is_displayed())
        self.driver.find_element(By.PARTIAL_LINK_TEXT, "Vaata kõiki õppematerjale").click()
        self.wait_for_text("Õppematerjalid")
        self.assertGreaterEqual(len(self.driver.find_elements(By.CSS_SELECTOR, "table.admin-table tbody tr")), 1)
        first_actions = self.driver.find_element(By.CSS_SELECTOR, "table.admin-table tbody tr .table-actions")
        self.assertIn("Muuda", first_actions.text)
        self.assertIn("Test", first_actions.text)
        self.assertIn("Kustuta", first_actions.text)

    def test_06_admin_can_create_and_delete_own_test_comment(self):
        self.login_admin()
        self.open("lessons?q=Present+simple")
        self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Present simple: daily routines"))).click()

        marker = f"Selenium E2E kommentaar {int(time.time())}"
        textarea = self.wait.until(EC.visibility_of_element_located((By.ID, "comment-body")))
        textarea.send_keys(marker)
        self.driver.find_element(By.CSS_SELECTOR, "form[action$='/comment'] button[type='submit']").click()
        self.wait_for_text(marker)

        comment = self.wait.until(EC.presence_of_element_located((
            By.XPATH,
            f"//div[contains(@class,'comment')][.//p[contains(normalize-space(.), {self.xpath_literal(marker)})]]"
        )))
        delete_button = comment.find_element(By.CSS_SELECTOR, "button.delete-link")
        delete_button.click()
        try:
            alert = WebDriverWait(self.driver, 3).until(EC.alert_is_present())
            alert.accept()
        except TimeoutException:
            pass
        self.wait.until(EC.invisibility_of_element_located((
            By.XPATH,
            f"//div[contains(@class,'comment')][.//p[contains(normalize-space(.), {self.xpath_literal(marker)})]]"
        )))


if __name__ == "__main__":
    unittest.main(verbosity=2)
