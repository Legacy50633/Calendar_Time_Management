import subprocess

packages = ["RPi.GPIO", "requests", "pygame","pytest"]

for package in packages:
    try:
        subprocess.check_call(["pip", "install", package])
        print(f"Successfully installed {package}")
    except subprocess.CalledProcessError as e:
        print(f"Error installing {package}: {e}")
