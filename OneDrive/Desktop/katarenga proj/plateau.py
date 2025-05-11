import random

class Plateau:
    def __init__(self):
        self.nb_cases = 8
        self.cases = [["bleu" for _ in range(self.nb_cases)] for _ in range(self.nb_cases)]
        self.quadrants_disponibles = self.definir_quadrants()
        self.initialiser_quadrants()

    def definir_quadrants(self):
    
        quadrants = [
          
            [
                ["bleu", "vert", "bleu", "vert"],
                ["jaune", "rouge", "jaune", "rouge"],
                ["bleu", "vert", "bleu", "vert"],
                ["jaune", "rouge", "jaune", "rouge"]
            ],
           
            [
                ["bleu", "vert", "jaune", "rouge"],
                ["vert", "jaune", "rouge", "bleu"],
                ["jaune", "rouge", "bleu", "vert"],
                ["rouge", "bleu", "vert", "jaune"]
            ],
          
            [
                ["bleu", "jaune", "vert", "rouge"],
                ["jaune", "vert", "rouge", "bleu"],
                ["vert", "rouge", "bleu", "jaune"],
                ["rouge", "bleu", "jaune", "vert"]
            ],
          
            [
                ["vert", "vert", "bleu", "bleu"],
                ["jaune", "jaune", "rouge", "rouge"],
                ["rouge", "rouge", "jaune", "jaune"],
                ["bleu", "bleu", "vert", "vert"]
            ],
           
            [
                ["bleu", "vert", "jaune", "rouge"],
                ["rouge", "bleu", "vert", "jaune"],
                ["jaune", "rouge", "bleu", "vert"],
                ["vert", "jaune", "rouge", "bleu"]
            ],
            
            [
                ["jaune", "vert", "vert", "jaune"],
                ["vert", "bleu", "rouge", "vert"],
                ["vert", "rouge", "bleu", "vert"],
                ["jaune", "vert", "vert", "jaune"]
            ],
       
            [
                ["bleu", "bleu", "bleu", "bleu"],
                ["vert", "vert", "vert", "vert"],
                ["jaune", "jaune", "jaune", "jaune"],
                ["rouge", "rouge", "rouge", "rouge"]
            ],
           
            [
                ["bleu", "vert", "jaune", "rouge"],
                ["bleu", "vert", "jaune", "rouge"],
                ["bleu", "vert", "jaune", "rouge"],
                ["bleu", "vert", "jaune", "rouge"]
            ]
        ]
        return quadrants

    def initialiser_quadrants(self):
   
        selected_quadrants = random.sample(self.quadrants_disponibles, 4)

        def rotate_quadrant(quadrant, rotation):
            if rotation == 0:
                return quadrant
            elif rotation == 90:
                return [[quadrant[3-j][i] for j in range(4)] for i in range(4)]
            elif rotation == 180:
                return [[quadrant[3-i][3-j] for j in range(4)] for i in range(4)]
            elif rotation == 270:
                return [[quadrant[j][3-i] for j in range(4)] for i in range(4)]

        for idx, (start_x, start_y) in enumerate([(0, 0), (4, 0), (0, 4), (4, 4)]):
            rotation = random.choice([0, 90, 180, 270])
            quadrant = rotate_quadrant(selected_quadrants[idx], rotation)
            for y in range(4):
                for x in range(4):
                    self.cases[start_y + y][start_x + x] = quadrant[y][x]

    def get_case(self, x, y):
        if 0 <= x < self.nb_cases and 0 <= y < self.nb_cases:
            return self.cases[y][x]
        return None

    def placer_quadrant(self, quadrant, rotation, position):
        pass  

    def sauvegarder_config(self):
        """Sauvegarde la configuration (à implémenter plus tard)."""
        print("Configuration sauvegardée (fonctionnalité à implémenter)")

    def reinitialiser_quadrants(self):
        self.initialiser_quadrants()