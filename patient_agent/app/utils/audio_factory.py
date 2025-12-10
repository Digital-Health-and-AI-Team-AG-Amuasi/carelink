import subprocess
from pathlib import Path

import ffmpeg

from app.config.constants import BASE_DIR


# logging.basicConfig(level=logging.DEBUG, filename=ERROR_FILE_DIR)

def convert_audio_to_wav(path_to_downloaded_audio_message: Path):
    try:

        if str(path_to_downloaded_audio_message).endswith('.oga'):
            path_to_converted_audio_message = path_to_downloaded_audio_message.with_suffix(".wav")
            command = ["/home/local_storage/ffmpeg", '-i', str(path_to_downloaded_audio_message),
                       str(path_to_converted_audio_message)]

        elif str(path_to_downloaded_audio_message).endswith('.wav'):
            path_to_converted_audio_message = path_to_downloaded_audio_message.with_suffix('.ogg')
            command = ["/home/local_storage/ffmpeg", '-i', str(path_to_downloaded_audio_message), "-c:a", "libopus",
                       "-b:a", "64k", str(path_to_converted_audio_message)]
        else:
            raise ValueError(f"The file '{path_to_downloaded_audio_message}' must be an 'oga' or 'wav' audio file")

        convert_audio_subproc = subprocess.run(command, capture_output=True, text=True)
        convert_audio_subproc.check_returncode()
    except subprocess.CalledProcessError as error:
        print("Command failed with error code:", error.returncode)
        print("Error output: ", error.stderr)
        return False, error.stderr
    else:
        print("Successfully converted file")
        print(f"path to {path_to_converted_audio_message}")
        return True, path_to_converted_audio_message


def convert_wav_to_ogg(input_path: Path, bitrate: str = '64k') -> Path:
    if not str(input_path).endswith('.wav'):
        raise ValueError("Input file must have a .wav extension")

    output_path = input_path.with_suffix('.ogg')

    try:
        (
            ffmpeg
            .input(str(input_path))
            .output(str(output_path), acodec='libopus', audio_bitrate=bitrate)
            .run(overwrite_output=True)
        )
        print(f"Successfully converted {input_path} to OGG: {output_path}")
        return output_path
    except ffmpeg.Error as e:
        print(f"Failed to convert WAV to OGG: {e.stderr.decode()}")
        raise



if __name__ == "__main__":
    input_file = Path("C:/Users/joshu/PycharmProjects/C.A.R.E-LLM/local_storage") / "messages/audio_messages/gnlp_audio_20250705_141241.wav"
    print(input_file)

    convert_wav_to_ogg(Path(input_file))