import pygame
from plateau import Plateau
from pion import PionManager

class UI:
    def __init__(self, screen, taille_case, marge):
        self.screen = screen
        self.base_taille_case = taille_case
        self.base_marge = marge
        self.taille_case = taille_case
        self.marge_x = marge
        self.marge_y = marge 
        self.nb_cases = 8
        self.plateau = Plateau()
        self.pion_manager = PionManager(self.taille_case, self.marge_x, self.marge_y, self.plateau)
        self.font = pygame.font.Font(None, 24)  
        self.timer = 60
        self.joueur_actuel = 0
        self.temps_depart = pygame.time.get_ticks()
        self.is_visible = True
        self.is_enabled = True
        self.is_running = True
        self.game_state = "playing"
        self.width = screen.get_width()
        self.height = screen.get_height()

       
        self.themes = ["plateauvide.png", "plateauvide_alt.png"]
        self.current_theme = 0
        try:
            self.plateau_fond_base = pygame.image.load(self.themes[self.current_theme]).convert_alpha()
            self.case_bleue_base = pygame.image.load("bluee.jpg").convert_alpha()
            self.case_verte_base = pygame.image.load("vertt.jpg").convert_alpha()
            self.case_jaune_base = pygame.image.load("jaunee.jpg").convert_alpha()
            self.case_rouge_base = pygame.image.load("rougee.jpg").convert_alpha()
        except FileNotFoundError as e:
            print(f"Erreur de chargement d'image : {e}")
            raise

        self.plateau_fond = self.plateau_fond_base
        self.case_bleue = self.case_bleue_base
        self.case_verte = self.case_verte_base
        self.case_jaune = self.case_jaune_base
        self.case_rouge = self.case_rouge_base

        self.ajuster_affichage()

    def ajuster_affichage(self):
        window_width, window_height = self.screen.get_size()
        max_plateau_size = min(window_width - 200, window_height - 200) 
        self.taille_case = max_plateau_size // self.nb_cases
        self.marge_x = (window_width - self.taille_case * self.nb_cases) // 2
        self.marge_y = (window_height - self.taille_case * self.nb_cases) // 4
        if self.marge_x < 100:
            self.marge_x = 100
            self.taille_case = (window_width - 2 * self.marge_x) // self.nb_cases
        if self.marge_y < 100:
            self.marge_y = 100
            self.taille_case = (window_height - 2 * self.marge_y) // self.nb_cases

        plateau_size = self.taille_case * self.nb_cases
        fond_size = int(plateau_size * 1.25)
        if not hasattr(self, 'cached_plateau_size') or self.cached_plateau_size != plateau_size:
            self.cached_plateau_size = plateau_size
            self.plateau_fond = pygame.transform.scale(self.plateau_fond_base, (fond_size, fond_size))
            self.case_bleue = pygame.transform.scale(self.case_bleue_base, (self.taille_case, self.taille_case))
            self.case_verte = pygame.transform.scale(self.case_verte_base, (self.taille_case, self.taille_case))
            self.case_jaune = pygame.transform.scale(self.case_jaune_base, (self.taille_case, self.taille_case))
            self.case_rouge = pygame.transform.scale(self.case_rouge_base, (self.taille_case, self.taille_case))

        self.fond_offset = (fond_size - plateau_size) // 2
        self.pion_manager.taille_case = self.taille_case
        self.pion_manager.marge_x = self.marge_x
        self.pion_manager.marge_y = self.marge_y
        for pion in self.pion_manager.pions:
            pion.taille_case = self.taille_case

    def resize(self, width, height):
        self.width = width
        self.height = height
        self.ajuster_affichage()

    def dessiner_plateau(self):
        self.screen.fill((220, 220, 220))
        self.screen.blit(self.plateau_fond, (self.marge_x - self.fond_offset, self.marge_y - self.fond_offset))

        for y in range(self.nb_cases):
            for x in range(self.nb_cases):
                couleur = self.plateau.get_case(x, y)
                image_case = {
                    "bleu": self.case_bleue,
                    "vert": self.case_verte,
                    "jaune": self.case_jaune,
                    "rouge": self.case_rouge
                }[couleur]
                pos_x = self.marge_x + x * self.taille_case
                pos_y = self.marge_y + y * self.taille_case
                pygame.draw.rect(self.screen, (50, 50, 50), (pos_x + 2, pos_y + 2, self.taille_case, self.taille_case))
                self.screen.blit(image_case, (pos_x, pos_y))

        camp_positions = [(0, 0), (7, 0), (0, 7), (7, 7)]
        for x, y in camp_positions:
            rect_x = self.marge_x + x * self.taille_case
            rect_y = self.marge_y + y * self.taille_case
            pygame.draw.rect(self.screen, (255, 215, 0), (rect_x, rect_y, self.taille_case, self.taille_case), 3)
            star = pygame.Surface((self.taille_case // 2, self.taille_case // 2), pygame.SRCALPHA)
            pygame.draw.polygon(star, (255, 215, 0), [
                (self.taille_case // 4, 0),
                (self.taille_case // 2, self.taille_case // 2),
                (0, self.taille_case // 4),
                (self.taille_case // 2, self.taille_case // 4),
                (self.taille_case // 4, self.taille_case // 2)
            ])
            self.screen.blit(star, (rect_x + self.taille_case // 4, rect_y + self.taille_case // 4))

    def dessiner_timer(self):
        if self.is_running:
            temps_restant = max(0, self.timer - (pygame.time.get_ticks() - self.temps_depart) // 1000)
            if temps_restant <= 0:
                self.changer_tour()
        else:
            temps_restant = self.timer

        timer_text = self.font.render(f"Temps: {temps_restant}s - Joueur {self.joueur_actuel + 1}", True, (0, 0, 0))
        halo_text = self.font.render(f"Temps: {temps_restant}s - Joueur {self.joueur_actuel + 1}", True, (255, 255, 255))
       
        x_pos = self.width * 0.1
        text_rect = timer_text.get_rect(topleft=(x_pos, 20))
        halo_rect = text_rect.inflate(2, 2)
        background_rect = text_rect.inflate(15, 8)  # Réduit les marges du fond
        pygame.draw.rect(self.screen, (255, 255, 255), background_rect, border_radius=5)
        self.screen.blit(halo_text, halo_rect)
        self.screen.blit(timer_text, text_rect)
        player_colors = [(255, 0, 0), (0, 0, 255)]
        pygame.draw.circle(self.screen, player_colors[self.joueur_actuel], (text_rect.left - 15, text_rect.centery), 8)  # Réduit le cercle

    def draw(self, surface):
        if self.game_state != "playing":
            return
        self.dessiner_plateau()
        self.pion_manager.dessiner_tous(surface)
        self.dessiner_timer()

    def update(self):
        if self.game_state == "playing":
            self.check_game_over()

    def check_game_over(self):
        player0_camp = sum(1 for p in self.pion_manager.pions if p.joueur_id == 0 and p.in_camp)
        player1_camp = sum(1 for p in self.pion_manager.pions if p.joueur_id == 1 and p.in_camp)
        if player0_camp == 8:
            self.game_state = "game_over"
            print("Joueur 1 gagne !")
        elif player1_camp == 8:
            self.game_state = "game_over"
            print("Joueur 2 gagne !")

    def changer_tour(self):
        self.joueur_actuel = 1 - self.joueur_actuel
        self.temps_depart = pygame.time.get_ticks()
        self.is_running = True

    def on_mouse_down(self, x, y):
        self.pion_manager.gerer_clic((x, y))

    def on_mouse_move(self, x, y):
        pass

    def on_mouse_up(self, x, y):
        if self.pion_manager.pion_selectionne:
            if self.pion_manager.pion_selectionne.deplacer((x, y), self.taille_case, self.marge_x, self.marge_y, self.plateau, self.pion_manager.pions):
                self.changer_tour()
                self.pion_manager.pion_selectionne = None
            else:
                self.pion_manager.pion_selectionne = None