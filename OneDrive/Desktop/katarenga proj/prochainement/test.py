import pygame
from ui import UI

pygame.init()
screen = pygame.display.set_mode((800, 600))
ui = UI(screen, 50, 40)
ui.game_state = "playing"
ui.menu_active = False
running = True
while running:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False
    ui.update()
    screen.fill((255, 255, 255))
    ui.draw(screen)
    pygame.display.flip()
pygame.quit()