import time
import pygame as pyg
import requests

class ApiWorker:
    def __init__(self,dir_path,url) -> None:
        self.dir_path = dir_path
        self.elapsed_time=0
        self.start_time = 0
        self.end_time = 0
        self.url = url
        self.time_delay=0
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
            self.start_time = time.time()
            pyg.mixer.music.load(audio_path)
            pyg.mixer.music.play()
            print("Playing audio-path:", audio_path)
            while pyg.mixer.music.get_busy():
                pyg.time.wait(100)
                elapsed_time = time.time() - self.start_time
            time.sleep(0.5)
            print(f"playing audio-time: {elapsed_time}")
            return elapsed_time
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
                    self.start_time = 0
                    self.start_time = time.time()
                    for audio_path in audio_paths:
                        self.time_delay += self.play_audio(str(self.dir_path)+audio_path)
                        print(f"inside loop: {self.time_delay}")
                    self.end_time = 0    
                    self.end_time = time.time()
                    print(f'loop finish Song played Time: {self.end_time - self.start_time} and delay time: {self.time_delay}')
                    if round(self.end_time - self.start_time) < 60:
                        print(f"time : {self.end_time - self.start_time} and sleep time: {round( ( 60 - self.time_delay ) + 2)}")
                        try:
                            time.sleep(round( ( 60 - self.time_delay ) + 2))
                        except:
                            pass
                    else:
                        time.sleep(1)  # Delay after playing API audio

        except KeyboardInterrupt:  # Handle program termination gracefully
            pass  # No cleanup needed for pygame in this case

        finally:
            pyg.quit()  # Quit Pygame
if __name__ == "__main__":
    dir_path="/var/www/html/calendar/"
    api_url='http://localhost/calendar/fetchapi.php'
    Worker2 = ApiWorker(dir_path,api_url)
    Worker2.main()