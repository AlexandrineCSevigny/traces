<?php
/**
 * @Project Traces - Parution
 * @author Alexandrine C. Sevigny <asevigny@outlook.fr>
 * @date Septembre 2019 - Version 1.0.1
 */
declare(strict_types=1);

namespace App\Session;

use App\Modeles\Livre;
use App\App;
use App\Utilitaires;
use App\Modeles\ModeLivraison;
use DateTime;

class SessionPanier
{

    private $items = [];
    private $session = null;

    public function __construct()
    {
        $this->session = App::getInstance()->getSession();
        $this->setModeLivraison('standard');
    }

    // Ajoute un item au panier avec la qantité X
    // Attention: Si l'item existe déjà dans le panier alors mettre à jour la quantité (la quantité maximum est de 10 à valider...)
    public function ajouterItem(Livre $unLivre, int $uneQte): void
    {
        if (isset($this->items[$unLivre->isbn]) === false) {
            $item = new SessionItem($unLivre, $uneQte);
            $this->items[$unLivre->isbn] = ($item);

        } else {
            $this->items[$unLivre->isbn]->quantite = $this->items[$unLivre->isbn]->quantite + $uneQte;

            if ($this->items[$unLivre->isbn]->quantite > 10) {
                $this->items[$unLivre->isbn]->quantite = 10;
            }
        }
        $this->sauvegarder();
    }

    // Supprimer un item du panier
    public function supprimerItem(string $isbn): void
    {
        unset($this->items[$isbn]);
        $this->sauvegarder();
    }

    // Retourner le tableau d'items du panier
    public function getItems(): array
    {
        return $this->items;
    }

    // Mettre à jour la quantité d'un item
    public function setQuantiteItem(string $isbn, int $uneQte): void
    {
        $this->items[$isbn]->quantite = $uneQte;
        $this->sauvegarder();
    }

    // Retourner la quantité d'un item
    public function getQuantiteItem(string $isbn): int
    {
        return $this->items[$isbn]->quantite;
    }

    // Retourner le nombre d'item différents (unique) dans le panier
    public function getNombreTotalItemsDifferents(): int
    {
        $nbTotal = count($this->items);
        return $nbTotal;
    }

    // Retourner le nombre de livres total dans le panier (somme de la quantité de chaque item)
    public function getNombreTotalItems(): int
    {
        $nbItem = 0;

        foreach ($this->items as $item) {
            $nbItem = $nbItem + $item->quantite;
        }
        return $nbItem;
    }

    // Retourner le montant sousTotal du panier (somme des montantTotals de chaque item)
    public function getMontantSousTotal(): string
    {
        $sousTotal = 0;

        foreach ($this->items as $item) {
            $prix = $item->getMontantTotal();
            $sousTotal = $sousTotal + (float)$prix;
        }

        return (string) $sousTotal;
    }

    // Retourner de montant de la TPS
    // TPS = 5%
    public function getMontantTPS(): string
    {
        $tps = (float)$this->getMontantSousTotal() * 0.05;

        return (string) $tps;
    }

    // Retourner le montant des frais de livraison
    // Frais de livraison (base=4$ + taux par item=3,50$) Exemple, 1livre=7,50$, 2livres=11$ etc.
    // Il n’y a pas de taxes sur les frais de livraison. Ils s’ajoutent en dernier.
    public function getMontantFraisLivraison(): string
    {
        $nbItems = $this->getNombreTotalItems();

        $livraison = ($nbItems * $_SESSION['livraison']->par_item);
        $livraison = $livraison + $_SESSION['livraison']->base;

        return (string) $livraison;
    }

    public function setModeLivraison($mode) {
        $livraison = ModeLivraison::trouverModeLivraison($mode);

        if ( $this->session->getItem('livraison') === null ) {
            $this->session->setItem('livraison', $livraison);
        } else {
            $this->session->supprimerItem('livraison');
            $this->session->setItem('livraison', $livraison);
        }
    }

    public function getDateEstimee() {
        $delai = $this->session->getItem('livraison')->delai_max_jrs;
        $today = new DateTime('now');

        $today->add(new \DateInterval('P'.$delai.'D'));

        return $today->format('Y-m-d H:i:s');
    }

    // Retourner le montant total de la commande (montant sous-total + TPS + montant livraison)
    public function getMontantTotal(): string
    {
        $sousTotal = (float)$this->getMontantSousTotal();
        $tps = (float)$this->getMontantTPS();
        $livraison = (float)$this->getMontantFraisLivraison();

        $total = $sousTotal + $tps + $livraison;

        return (string) $total;
    }

    public function getMontantItem($isbn) {
        return $this->items[$isbn]->getMontantTotal();
    }

    public function recalculerPanier(): array {
        $sousTotal = (float)$this->getMontantSousTotal();
        $tps = (float)$this->getMontantTPS();
        $livraison = (float)$this->getMontantFraisLivraison();
        $total = $sousTotal + $tps + $livraison;

        $sousTotal = $this->formaterPrix((string) $sousTotal);
        $tps = $this->formaterPrix((string) $tps);
        $livraison = $this->formaterPrix((string)$livraison);
        $total = $this->formaterPrix((string)$total);

        $arrPanier = array( "sousTotal" => $sousTotal, "tps"=>$tps, "livraison"=>$livraison, "total"=>$total );

        return $arrPanier;
    }

    public function formaterPrix(string $prix): string
    {
        $prix = Utilitaires::formaterPrix( $prix );
        return (string) $prix;
    }

    // Créer le message de confirmation d'ajout d'item au panier
    public function creerMessage($quantite)
    {
        $this->session->setItem('message', $quantite);
    }

    // Retourner le message de confirmation d'ajout d'item de panier
    public function getMessage()
    {
        $message = $this->session->getItem('message');
        return $message;
    }

    // Supprimer le message de la session
    public function supprimerMessage()
    {
        $this->session->supprimerItem('message');
    }

    // Sauvegarder le panier en variable session nommée: panier
    public function sauvegarder(): void
    {
        $this->session->setItem('panier', $this);
    }

    // Supprimer le panier en variable session nommée: panier
    public function supprimer()
    {
        $this->session->supprimerItem('panier');
    }
}