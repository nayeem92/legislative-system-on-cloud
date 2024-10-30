from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.firefox.service import Service
import time

# Specify the path to geckodriver
gecko_service = Service('D:/path_to_your_extracted_geckodriver/geckodriver.exe')

# Initialize the WebDriver
driver = webdriver.Firefox(service=gecko_service)

try:
    # Open the login page
    driver.get("http://localhost/legislative-demo/public/login.php")

    # Give some time for the page to load
    time.sleep(2)

    # Find the username and password fields (replace 'username' and 'password' with actual field names or IDs)
    username_input = driver.find_element(By.NAME, 'username')
    password_input = driver.find_element(By.NAME, 'password')

    # Enter your login credentials
    username_input.send_keys("your_username")
    password_input.send_keys("your_password")

    # Submit the form (you might need to adjust this part)
    password_input.send_keys(Keys.RETURN)

    # Optionally, wait for the login to complete
    time.sleep(5)

finally:
    # Close the browser
    driver.quit()
