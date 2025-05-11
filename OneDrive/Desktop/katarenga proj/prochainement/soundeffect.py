import pygame

class SoundEffect:
    __click_sound = None
    __move_sound = None
    __turn_sound = None

    @classmethod
    def init(cls):
        pygame.mixer.init()
        try:
            cls.__click_sound = pygame.mixer.Sound("click.wav")
            cls.__move_sound = pygame.mixer.Sound("move.wav")
            cls.__turn_sound = pygame.mixer.Sound("turn.wav")
        except FileNotFoundError:
            print("Audio files (click.wav, move.wav, turn.wav) not found. Sound effects will be disabled.")

    @classmethod
    def play_click_sound(cls):
        if cls.__click_sound:
            cls.__click_sound.play()

    @classmethod
    def play_move_sound(cls):
        if cls.__move_sound:
            cls.__move_sound.play()

    @classmethod
    def play_turn_sound(cls):
        if cls.__turn_sound:
            cls.__turn_sound.play()