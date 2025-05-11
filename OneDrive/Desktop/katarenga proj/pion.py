import pygame

class Pion:
    def __init__(self, x, y, joueur_id, taille_case, couleur=(255, 0, 0)):
        self.x = x
        self.y = y
        self.joueur_id = joueur_id
        self.taille_case = taille_case
        self.couleur = couleur if joueur_id == 0 else (0, 0, 255)  # rouge pour J1, bleu pour J2
        self.plateau = None
        self.in_camp = False

    def dessiner(self, surface, marge_x, marge_y):
        
        if self.in_camp:
            radius = max(10, self.taille_case // 6) 
            pos_x = marge_x + self.x * self.taille_case + self.taille_case // 2
            pos_y = marge_y + self.y * self.taille_case + self.taille_case // 2
            pygame.draw.circle(surface, self.couleur, (pos_x, pos_y), radius)
            pygame.draw.circle(surface, (0, 0, 0), (pos_x, pos_y), radius, 2) 
            return

        radius = max(15, (self.taille_case - 10) // 2)
        pos_x = marge_x + self.x * self.taille_case + self.taille_case // 2
        pos_y = marge_y + self.y * self.taille_case + self.taille_case // 2
        pygame.draw.circle(surface, self.couleur, (pos_x, pos_y), radius)
        pygame.draw.circle(surface, (0, 0, 0), (pos_x, pos_y), radius, 2)  

    def est_clique(self, pos, marge_x, marge_y):
        rect = pygame.Rect(marge_x + self.x * self.taille_case, marge_y + self.y * self.taille_case, self.taille_case, self.taille_case)
        return rect.collidepoint(pos)

    def peut_deplacer(self, new_x, new_y, plateau, pions, allow_capture=True):
        
        if not (0 <= new_x < 8 and 0 <= new_y < 8) or self.in_camp:
            return False

        if self.joueur_id == 0 and self.y == 7:
            if (new_x, new_y) in [(0, 7), (7, 7)]:
                return not any(p.x == new_x and p.y == new_y for p in pions)
        elif self.joueur_id == 1 and self.y == 0:
            if (new_x, new_y) in [(0, 0), (7, 0)]:
                return not any(p.x == new_x and p.y == new_y for p in pions)

        target_pion = next((p for p in pions if p.x == new_x and p.y == new_y and not p.in_camp), None)
        if target_pion:
            if not allow_capture or target_pion.joueur_id == self.joueur_id:
                return False
        else:
            if any(p.x == new_x and p.y == new_y and not p.in_camp for p in pions):
                return False

        couleur_case = plateau.get_case(self.x, self.y)
        dx = new_x - self.x
        dy = new_y - self.y

        if couleur_case == "bleu":
            return max(abs(dx), abs(dy)) <= 1
        elif couleur_case == "vert":
            return (abs(dx) == 2 and abs(dy) == 1) or (abs(dx) == 1 and abs(dy) == 2)
        elif couleur_case == "jaune":
            if abs(dx) != abs(dy):
                return False
            step_x = 1 if dx > 0 else -1
            step_y = 1 if dy > 0 else -1
            for i in range(1, abs(dx)):
                check_x = self.x + i * step_x
                check_y = self.y + i * step_y
                if any(p.x == check_x and p.y == check_y and not p.in_camp for p in pions):
                    return False
                if plateau.get_case(check_x, check_y) == "jaune" and (check_x != new_x or check_y != new_y):
                    return False
            return True
        elif couleur_case == "rouge":
            if dx != 0 and dy != 0:
                return False
            if dx == 0:
                step = 1 if dy > 0 else -1
                for i in range(1, abs(dy)):
                    check_y = self.y + i * step
                    if any(p.x == self.x and p.y == check_y and not p.in_camp for p in pions):
                        return False
                    if plateau.get_case(self.x, check_y) == "rouge" and check_y != new_y:
                        return False
            else:
                step = 1 if dx > 0 else -1
                for i in range(1, abs(dx)):
                    check_x = self.x + i * step
                    if any(p.x == check_x and p.y == self.y and not p.in_camp for p in pions):
                        return False
                    if plateau.get_case(check_x, self.y) == "rouge" and check_x != new_x:
                        return False
            return True
        return False

    def deplacer(self, nouvelle_pos, taille_case, marge_x, marge_y, plateau, pions, allow_capture=True):
       
        new_x = (nouvelle_pos[0] - marge_x) // taille_case
        new_y = (nouvelle_pos[1] - marge_y) // taille_case
        if self.peut_deplacer(new_x, new_y, plateau, pions, allow_capture):
            camp_move = False
            if self.joueur_id == 0 and self.y == 7 and (new_x, new_y) in [(0, 7), (7, 7)]:
                camp_move = True
            elif self.joueur_id == 1 and self.y == 0 and (new_x, new_y) in [(0, 0), (7, 0)]:
                camp_move = True

            target_pion = next((p for p in pions if p.x == new_x and p.y == new_y and not p.in_camp), None)
            if target_pion and allow_capture:
                pions.remove(target_pion)

            self.x, self.y = new_x, new_y
            if camp_move:
                self.in_camp = True
            return True
        return False

class PionManager:
    def __init__(self, taille_case, marge_x, marge_y, plateau):
        self.taille_case = taille_case
        self.marge_x = marge_x
        self.marge_y = marge_y
        self.plateau = plateau
        self.pions = []
        self.pion_selectionne = None
        self.initialiser_pions()

    def initialiser_pions(self):
        
        self.pions.clear()
        for x in range(8):
            pion1 = Pion(x, 0, 0, self.taille_case, (255, 0, 0))
            pion1.plateau = self.plateau
            pion2 = Pion(x, 7, 1, self.taille_case, (0, 0, 255))
            pion2.plateau = self.plateau
            self.pions.extend([pion1, pion2])

    def dessiner_tous(self, surface):
     
        for pion in self.pions:
            pion.dessiner(surface, self.marge_x, self.marge_y)

    def gerer_clic(self, pos, allow_capture=True):
       
        if self.pion_selectionne:
            if self.pion_selectionne.deplacer(pos, self.taille_case, self.marge_x, self.marge_y, self.plateau, self.pions, allow_capture):
                self.pion_selectionne = None
        else:
            for pion in self.pions:
                if pion.est_clique(pos, self.marge_x, self.marge_y):
                    self.pion_selectionne = pion
                    break