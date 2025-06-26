<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PropertyCard extends Component
{
    public $title;
    public $description;
    public $price;
    public $image;
    public $propertyId;
    public $rating;
    public $tags;
    public $promo;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param string $description
     * @param float|int $price
     * @param string $image
     * @param int $propertyId
     * @param int $rating
     * @param array $tags
     * @param bool $promo
     */
    public function __construct($title, $description, $price, $image, $propertyId, $rating = 0, $tags = [], $promo = false)
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->propertyId = $propertyId;
        $this->rating = $rating;
        $this->tags = $tags;
        $this->promo = $promo;
    }

    public function render()
    {
        return view('components.property-card');
    }
}
