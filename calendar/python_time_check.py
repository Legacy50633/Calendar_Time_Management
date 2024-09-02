import time
from datetime import datetime,timedelta

start_time = datetime.now()
end_time = start_time + timedelta(minutes=3)  # Target duration

while datetime.now() < end_time:
  # Busy wait with a short delay to avoid overwhelming CPU
  start = time.perf_counter()  # High-resolution timer
  while time.perf_counter() - start < 30.0:
    pass  # Do nothing for a very short time

  print("Hello world")

print("Finished printing after 5 minutes.")