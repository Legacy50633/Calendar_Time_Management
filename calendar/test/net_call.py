import pytest
from unittest.mock import patch, MagicMock
import playsound as ps

# Sample test data
SAMPLE_API_RESPONSE = [
    {"audio": "/var/www/html/calendar/upload/school.mp3"},
    {"audio": "/var/www/html/calendar/upload/test.mp3"},
]
DEFAULT_AUDIO_PATH = "/var/www/html/calendar/upload/school.mp3"


@pytest.mark.parametrize(
    "mocked_response, expected_data",
    [
        (MagicMock(json=lambda: SAMPLE_API_RESPONSE), SAMPLE_API_RESPONSE),
        (MagicMock(json=lambda: None), None),
    ],
)
@patch("requests.get")
def test_get_data_from_api(mock_get, mocked_response, expected_data):
    mock_get.return_value = mocked_response
    data = get_data_from_api()
    assert data == expected_data


@patch("pygame.mixer.music")
def test_play_audio(mock_music):
    audio_path = "/path/to/audio.mp3"
    play_audio(audio_path)
    mock_music.load.assert_called_once_with(audio_path)
    mock_music.play.assert_called_once()


@patch("pygame.mixer.init")
@patch("pygame.init")
@patch("time.sleep")
@patch("calendar_script.get_data_from_api", return_value=SAMPLE_API_RESPONSE)
@patch("calendar_script.play_audio")
@patch("calendar_script.GPIO")
def test_main(
    mock_gpio, mock_play_audio, mock_get_data_from_api, mock_sleep, mock_pygame_init, mock_pygame_mixer_init
):
    mock_gpio.input.return_value = False  # Simulate button press
    main()

    mock_gpio.setmode.assert_called_once()
    mock_gpio.setup.assert_called_once()
    mock_pygame_init.assert_called_once()
    mock_pygame_mixer_init.assert_called_once()

    mock_get_data_from_api.assert_called_once()
    mock_play_audio.assert_called_with(DEFAULT_AUDIO_PATH)

    assert mock_sleep.call_count == 2  # Should be called twice in the loop


if __name__ == "__main__":
    pytest.main()
