import pygame as p
from main import main
import sys

# Dimensions de l'écran
LARGEUR_ECRAN = 800
HAUTEUR_ECRAN = 800

class MenuKatarenga():
    def __init__(self):
        p.init()
        self.ecran_principal = p.display.set_mode((LARGEUR_ECRAN, HAUTEUR_ECRAN))
        
        p.display.set_icon(p.image.load("c:\\Users\\esthe\\OneDrive\\Desktop\\katarenga proj\\view\\resources\\images\\logo.jpeg"))
        p.display.set_caption("Katarenga AI")
        self.ecran_principal.fill((0, 0, 0))
        p.display.set_caption("Katarenga Game")

        
        image_fond = p.image.load("c:\\Users\\esthe\\OneDrive\\Desktop\\katarenga proj\\view\\resources\\images\\plateauRempli.png").convert()

       
        COULEUR_NOIRE = (0, 0, 0)
        COULEUR_BLANCHE = (255, 255, 255)

        LARGEUR_BOUTON, HAUTEUR_BOUTON = 180, 70
        POSITION_X_BOUTON = LARGEUR_ECRAN // 2 - LARGEUR_BOUTON // 2
        POSITION_Y_BOUTON_JOUER = HAUTEUR_ECRAN // 2 - HAUTEUR_BOUTON // 2

       
        police_texte = p.font.Font("C:\\Users\\esthe\\OneDrive\\Desktop\\katarenga proj\\view\\resources\\font\\Merriweather-Bold.ttf", 40)

        en_cours = True  

        while en_cours:
            for evenement in p.event.get():
                if evenement.type == p.QUIT:
                    p.quit()
                    sys.exit()

           
            self.ecran_principal.blit(image_fond, (100, 100))

           
            bouton_jouer = p.draw.rect(self.ecran_principal, COULEUR_NOIRE, (POSITION_X_BOUTON, POSITION_Y_BOUTON_JOUER, LARGEUR_BOUTON, HAUTEUR_BOUTON))

            texte_bouton_jouer = police_texte.render("Jouer", True, COULEUR_BLANCHE)
            rect_texte_bouton_jouer = texte_bouton_jouer.get_rect(center=(POSITION_X_BOUTON + LARGEUR_BOUTON // 2, POSITION_Y_BOUTON_JOUER + HAUTEUR_BOUTON // 2))
            self.ecran_principal.blit(texte_bouton_jouer, rect_texte_bouton_jouer)

            ombre_titre = police_texte.render("Bienvenue à Katarenga AI", True, COULEUR_NOIRE)
            texte_titre = police_texte.render("Bienvenue à Katarenga AI", True, COULEUR_BLANCHE)
            self.ecran_principal.blit(ombre_titre, (LARGEUR_ECRAN // 2 - texte_titre.get_width() // 2 + 2, 52))
            self.ecran_principal.blit(texte_titre, (LARGEUR_ECRAN // 2 - texte_titre.get_width() // 2, 50))

         
            position_souris = p.mouse.get_pos()
            if p.mouse.get_pressed()[0]:
                if bouton_jouer.collidepoint(position_souris):
                    print("Amusez-vous bien :D")
                    en_cours = False 

            p.display.flip()

       
        p.quit()
        jeu_principal = main()
        jeu_principal.main()

if __name__ == "__main__":
    MenuKatarenga()
