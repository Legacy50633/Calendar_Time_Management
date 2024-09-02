import time
import pygame as pyg
import requests
from datetime import datetime,timedelta

class ApiWorker:
    def __init__(self,dir_path,url) -> None:
        self.dir_path = dir_path
        self.elapsed_time = 0
        self.start_time = 0
        self.end_time = 0
        self.url = url
        self.time_delay=0
        self.set_volume = 0.8
        self.timeout= 0
        self.init_pygame()
    
    def init_pygame(self)  -> None:
        ''' default init for pygame '''
        pyg.init()
        pyg.mixer.init()
    
    def get_data_from_api(self):
        ''' this localhost api for playing audio in raspberry-pi '''
        try:
            response = requests.get(self.url)
            response.raise_for_status()
            try:
                data = response.json()
                return data
            except ValueError:
                return None
        except Exception as e:
            print(f"Error: {e}")
            time.sleep(1)
            
    def play_audio(self,audio_path):
        """Plays a specified audio file using Pygame mixer."""
        try:
            pyg.mixer.music.load(audio_path)
            pyg.mixer.music.play()
            pyg.mixer.music.set_volume(self.set_volume)
            print("Playing audio-path:", audio_path)
            while pyg.mixer.music.get_busy():
                pyg.time.wait(100)
            time.sleep(0.5)
        except Exception as e:
            print("Error playing audio:", audio_path, e)
            
    def main(self):
        try:
            while True:
                # Check for API data
                audio_data = self.get_data_from_api()
                # Play audio from API response
                if audio_data:
                    audio_paths = [audio_item['audio'] for audio_item in audio_data]
                    print(f"number of audio get from api is:  {len(audio_paths)}")
                    for audio_path in audio_paths:
                        self.play_audio(str(self.dir_path)+audio_path)
                        print(f"inside loop: {self.time_delay}")
                    time.sleep(60)

        except KeyboardInterrupt:  # Handle program termination gracefully
            pass  # No cleanup needed for pygame in this case

        finally:
            pyg.quit()  # Quit Pygame
if __name__ == "__main__":
    dir_path="/var/www/html/calendar/"
    api_url='http://localhost/calendar/fetchapi.php'
    Worker2 = ApiWorker(dir_path,api_url)
    Worker2.main()