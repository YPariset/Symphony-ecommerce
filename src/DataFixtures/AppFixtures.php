<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $imageData = [
            "https://www.tsume-art.com/storage/app/uploads/public/8ac/541/91b/thumb__660x920_0_0_crop.jpg",
            "https://www.tsume-art.com/storage/app/uploads/public/a1d/4aa/063/thumb__660x920_0_0_crop.jpg",
            "https://www.tsume-art.com/storage/app/uploads/public/b76/215/756/thumb__660x920_0_0_crop.jpg",
            "https://www.tsume-art.com/storage/app/uploads/public/6e1/662/5f3/thumb__660x920_0_0_crop.jpg",
        ];

        $nameData = [
            "Blackbeard ULTRA HQS",
            "Gaara - A father's hope, a mother's love",
            "FRIEZA 4th form HQS+",
            "Naruto & Kyubi - Linked by the Seal",
        ];

        $priceData = [
            2135.90,
            511.97,
            1111.11,
            511.97,
        ];

        $descriptionData = [
            "https://www.tsume-art.com/storage/app/uploads/public/598/19e/4a6/thumb_6257_0x100_0_0_auto.png",
            "https://www.tsume-art.com/storage/app/uploads/public/598/19e/4a0/thumb_6246_0x100_0_0_auto.png",
            "https://www.tsume-art.com/storage/app/uploads/public/598/19e/4ab/thumb_6260_0x100_0_0_auto.png",
            "https://www.tsume-art.com/storage/app/uploads/public/5c5/ada/c9c/thumb_13286_0x100_0_0_auto.png",
        ];
        // create products! Bam!
        for ($i = 0; $i < 4; $i++) {
            $product = new Product();
            $product->setName($nameData[$i]);
            $product->setPrice($priceData[$i]);
            $product->setDescription($descriptionData[$i]);
            $product->setImagePath($imageData[$i]);
           
            $manager->persist($product);
        }
        $manager->flush();
    }
}
