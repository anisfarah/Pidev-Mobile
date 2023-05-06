/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

/**
 *
 * @author Pc Anis
 */
public class LignePanier {
    private int id;
    private Livre livre;
    private Panier panier;
    private int quantite;

    public LignePanier() {
    }

    public LignePanier(int id, Livre livre, Panier panier, int quantite) {
        this.id = id;
        this.livre = livre;
        this.panier = panier;
        this.quantite = quantite;
    }

    public LignePanier(Livre livre, Panier panier, int quantite) {
        this.livre = livre;
        this.panier = panier;
        this.quantite = quantite;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public Livre getLivre() {
        return livre;
    }

    public void setLivre(Livre livre) {
        this.livre = livre;
    }

    public Panier getPanier() {
        return panier;
    }

    public void setPanier(Panier panier) {
        this.panier = panier;
    }

    public int getQuantite() {
        return quantite;
    }

    public void setQuantite(int quantite) {
        this.quantite = quantite;
    }

    @Override
    public String toString() {
        return "LignePanier{" + "id=" + id + ", livre=" + livre + ", panier=" + panier + ", quantite=" + quantite + '}';
    }
    
}
