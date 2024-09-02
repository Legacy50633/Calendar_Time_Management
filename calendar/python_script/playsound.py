import pygame
import requests
import time
dir_path = "/var/www/html/calendar"
elapsed_time=0
start_time = 0


def get_data_from_api():
    ''' this localhost api for playing audio in raspberry-pi '''
    try:
        response = requests.get('http://localhost/calendar/fetchapi.php')
        response.raise_for_status()
        try:
            data = response.json()
            return data
        except ValueError:
            print("No json data Present")
            return None
    except Exception as e:
        print(f"Error: {e}")
        time.sleep(1)

def play_audio(self,audio_path):
    """Plays a specified audio file using Pygame mixer."""
    try:
        pygame.mixer.music.load(audio_path)
        pygame.mixer.music.play()
        print("Playing audio:", audio_path)
        while pygame.mixer.music.get_busy():
            pygame.time.wait(100)
            elapsed_time = time.time() - self.start_time
    except Exception as e:
        print("Error playing audio:", audio_path, e)

def main():
    pygame.init()
    pygame.mixer.init()
    try:
        while True:
            # Check for API data
            audio_data = get_data_from_api()

            # Play audio from API response
            if audio_data:
                audio_paths = [audio_item['audio'] for audio_item in audio_data]
                for audio_path in audio_paths:
                    play_audio(str(dir_path)+audio_path)
                time.sleep(30)  # Delay after playing API audio

    except KeyboardInterrupt:  # Handle program termination gracefully
        pass  # No cleanup needed for pygame in this case

    finally:
        pygame.quit()  # Quit Pygame

if __name__ == '__main__':
    main()
