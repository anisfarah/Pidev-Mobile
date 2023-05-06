/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

import java.util.Date;


/**
 *
 * @author MSI
 */
public class Livre {
    
    private int idLivre ;
    private String libelle ;
    private String description ;
    private String editeur ;
    private Date date_edition ;
    private String categorie ;
    private float prix ;
    private String langue ;
    private String image;
    private Promo promo ;

    public Livre() {
    }

    public Livre(String libelle, String description, String editeur, Date date_edition, 
            String categorie, float prix, String langue, String image, Promo promo) {
        this.libelle = libelle;
        this.description = description;
        this.editeur = editeur;
        this.date_edition = date_edition;
        this.categorie = categorie;
        this.prix = prix;
        this.langue = langue;
        this.image = image;
        this.promo = promo;
    }
    
    public Livre(int idLivre, String libelle, String description, String editeur, Date date_edition, 
            String categorie, float prix, String langue, String image, Promo promo) {
        this.idLivre = idLivre;
        this.libelle = libelle;
        this.description = description;
        this.editeur = editeur;
        this.date_edition = date_edition;
        this.categorie = categorie;
        this.prix = prix;
        this.langue = langue;
        this.image = image;
        this.promo = promo;
    }

    public int getIdLivre() {
        return idLivre;
    }

    public void setIdLivre(int idLivre) {
        this.idLivre = idLivre;
    }

    public String getLibelle() {
        return libelle;
    }

    public void setLibelle(String libelle) {
        this.libelle = libelle;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getEditeur() {
        return editeur;
    }

    public void setEditeur(String editeur) {
        this.editeur = editeur;
    }

    public Date getDate_edition() {
        return date_edition;
    }

    public void setDate_edition(Date date_edition) {
        this.date_edition = date_edition;
    }

    public String getCategorie() {
        return categorie;
    }

    public void setCategorie(String categorie) {
        this.categorie = categorie;
    }

    public float getPrix() {
        return prix;
    }

    public void setPrix(float prix) {
        this.prix = prix;
    }

    public String getLangue() {
        return langue;
    }

    public void setLangue(String langue) {
        this.langue = langue;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public Promo getPromo() {
        return promo;
    }

    public void setPromo(Promo promo) {
        this.promo = promo;
    }

    @Override
    public String toString() {
        return "Livre{" + "idLivre=" + idLivre + ", libelle=" + libelle 
                + ", description=" + description + ", editeur=" + editeur 
                + ", date_edition=" + date_edition + ", categorie=" + categorie 
                + ", prix=" + prix + ", langue=" + langue + ", image=" + image 
                + ", promo=" + promo + '}';
    }
    
     
    
    
}
