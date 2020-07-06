<?php
/**
 * @Project Traces - Parution
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Session;

use App\Modeles\Livre;
use App\Utilitaires;

class SessionItem
{
    private $livre = null;
    private $quantite = 0;
    private $prix = 0;

    public function __construct(Livre $unLivre, int $uneQte)
    {
        $this->livre = $unLivre;
        $this->quantite = $uneQte;
        $this->prix = $this->livre->prix;
    }

    // Retourne le montant total d'un item (prix x quantité)
    public function getMontantTotal(): string
    {
        $montant = (int)($this->quantite) * (float)($this->prix);

        return (string) $montant;
    }

    // Formater le prix
    public function formaterPrix(string $prix): string
    {
        $prix = Utilitaires::formaterPrix($prix);

        return (string)$prix;
    }

    // Getter / Setter (magique)
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}