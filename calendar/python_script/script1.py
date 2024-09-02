import RPi.GPIO as GPIO
import time
import pygame as pyg
import requests
from datetime import datetime,timedelta


class GpioButton:
    def __init__(self, button_pin,button_pin2, first_audio, second_audio,start_audio) -> None:
        ''' setup for button_pin and first audio and second audio'''
        self.button_pin = button_pin
        self.first_audio = first_audio
        self.second_audio = second_audio
        self.start_audio = start_audio
        self.button_pin2= button_pin2
        self.queue = []
        self.flag =True
        self.init_gpio()
        self.init_pygame()
    
    def init_gpio(self)  -> None:
        ''' set gpio pin '''
        GPIO.setmode(GPIO.BCM)
        GPIO.setup(self.button_pin, GPIO.IN, pull_up_down=GPIO.PUD_UP)
        GPIO.setup(self.button_pin2, GPIO.OUT)
    
    def init_pygame(self)  -> None:
        ''' default init for pygame '''
        pyg.init()
        pyg.mixer.init()
    
    def play_audio(self, audio_path):
        try:
            pyg.mixer.music.load(audio_path)
            GPIO.output(self.button_pin2, GPIO.LOW)
            pyg.mixer.music.play()
            print("Playing audio:", audio_path)
            while pyg.mixer.music.get_busy():
                pyg.time.wait(100)
            time.sleep(2)
            GPIO.output(self.button_pin2, GPIO.HIGH)
        except Exception as e:
            print("Error playing audio:", audio_path, e)
    
    def main(self)  -> None:
        try:
            self.play_audio(self.start_audio)
            while True:
                now = datetime.now()
                current_time = now.strftime("%H:%M")  # Format current time as h:m
                if self.queue is not None and self.queue[0] == current_time:
                    self.play_audio(self.second_audio)
                    print("Playing is audio. After Five Minutue")
                    self.queue.pop()
                if GPIO.input(self.button_pin) == False:
                    print("Playing is audio. First Time")
                    self.play_audio(self.first_audio)
                    new_time = datetime.now() + timedelta(minutes=5)
                    formatted_new_time = new_time.strftime("%H:%M")
                    self.queue.insert(0,formatted_new_time)
        except Exception as K:
            print("Audio not playing error:", K)
        finally:
            pyg.quit()  # Quit Pygame
            GPIO.cleanup()

if __name__ == "__main__":
    button_pin = 17  # Pin for push button
    button_pin2 = 14 # Pin for led
    first_audio = "/var/www/html/calendar/upload/school.mp3"
    second_audio = "/var/www/html/calendar/upload/school.mp3"
    start_audio = "/var/www/html/calendar/upload/booting_announce.mp3"
    Worker1 = GpioButton(button_pin,button_pin2, first_audio, second_audio,start_audio)
    Worker1.main()