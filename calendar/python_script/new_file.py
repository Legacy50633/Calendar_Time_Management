from checking import check_api

# Call the check_api function
response = check_api()

print(response)
start_audio_file = response[0]['start_audio']
print(start_audio_file)
