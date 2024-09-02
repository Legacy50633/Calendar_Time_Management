import requests

def check_api():
    try:
        response = requests.get("http://localhost/calendar/gpio_api.php")
        response.raise_for_status()  # This will raise an HTTPError for bad responses
        try:
            data = response.json()
            return data
        except ValueError as json_error:  # This catches JSON decoding errors
            print('Failed to parse JSON response:', json_error)
    except requests.exceptions.RequestException as request_error:  # This is a base class for all requests exceptions
        print("API request failed:", request_error)

# Call the function to check the API
check_api()


    