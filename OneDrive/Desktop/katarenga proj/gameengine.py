import pygame
from ui import UI

class GameEngine:
    __screen = None
    __game_objects = []
    __is_running = False

    @classmethod
    def initialize(cls, width=800, height=600):
        cls.__screen = pygame.display.set_mode((width, height), pygame.RESIZABLE)
        pygame.display.set_caption("Kateranga")
        cls.__game_objects.append(UI(cls.__screen, 50, 40))
        print("GameEngine initialisé")

    @classmethod
    def start(cls):
        cls.__is_running = True
        clock = pygame.time.Clock()
        print("Début de la boucle principale")
        while cls.__is_running:
            cls.__handle_events()
            cls.__update_game_objects()
            cls.__render_game_objects()
            clock.tick(60)
        pygame.quit()
        print("Fin de la boucle principale")

    @classmethod
    def __handle_events(cls):
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                cls.__is_running = False
            elif event.type == pygame.VIDEORESIZE:
                cls.__screen = pygame.display.set_mode((event.w, event.h), pygame.RESIZABLE)
                for obj in cls.__game_objects:
                    obj.resize(event.w, event.h)
            elif event.type == pygame.MOUSEBUTTONDOWN:
                for obj in cls.__game_objects:
                    if obj.is_enabled and obj.is_visible:
                        obj.on_mouse_down(event.pos[0], event.pos[1])
            elif event.type == pygame.MOUSEMOTION:
                for obj in cls.__game_objects:
                    if obj.is_enabled and obj.is_visible:
                        obj.on_mouse_move(event.pos[0], event.pos[1])
            elif event.type == pygame.MOUSEBUTTONUP:
                for obj in cls.__game_objects:
                    if obj.is_enabled and obj.is_visible:
                        obj.on_mouse_up(event.pos[0], event.pos[1])

    @classmethod
    def __update_game_objects(cls):
        for obj in cls.__game_objects:
            if obj.is_enabled:
                obj.update()

    @classmethod
    def __render_game_objects(cls):
        cls.__screen.fill((220, 220, 220))  # Fond gris clair
        for obj in cls.__game_objects:
            if obj.is_visible:
                obj.draw(cls.__screen)
        pygame.display.flip()