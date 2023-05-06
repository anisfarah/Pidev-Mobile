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
public class Livre {
     private int id;
    
    private String libelle;
    private String description;
    private String editeur;
    private String date_edition;
    private String categorie;
    private float prix;
    private String langue;
    private int promo;
    private int auteur;
    private String image;

    public Livre() {
    }

    public Livre(int id) {
        this.id = id;
    }

    public Livre(int id, String libelle, String description, String editeur, String date_edition, String categorie, float prix, String langue, int promo, int auteur, String image) {
        this.id = id;
        this.libelle = libelle;
        this.description = description;
        this.editeur = editeur;
        this.date_edition = date_edition;
        this.categorie = categorie;
        this.prix = prix;
        this.langue = langue;
        this.promo = promo;
        this.auteur = auteur;
        this.image = image;
    }

    public Livre(String libelle, String description, String editeur, String date_edition, String categorie, float prix, String langue, int promo, int auteur, String image) {
        this.libelle = libelle;
        this.description = description;
        this.editeur = editeur;
        this.date_edition = date_edition;
        this.categorie = categorie;
        this.prix = prix;
        this.langue = langue;
        this.promo = promo;
        this.auteur = auteur;
        this.image = image;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
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

    public String getDate_edition() {
        return date_edition;
    }

    public void setDate_edition(String date_edition) {
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

    public int getPromo() {
        return promo;
    }

    public void setPromo(int promo) {
        this.promo = promo;
    }

    public int getUser() {
        return auteur;
    }

    public void setUser(int auteur) {
        this.auteur = auteur;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    @Override
    public String toString() {
        return "Livre{" + "id=" + id + ", libelle=" + libelle + ", description=" + description + ", editeur=" + editeur + ", date_edition=" + date_edition + ", categorie=" + categorie + ", prix=" + prix + ", langue=" + langue + ", promo=" + promo + ", user=" + auteur + ", image=" + image + '}';
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    private int qte;
    private int idLigne;
    private float prixtot;
    private int idPanier;

    public int getIdPanier() {
        return idPanier;
    }

    public void setIdPanier(int idPanier) {
        this.idPanier = idPanier;
    }

    public float getPrixtot() {
        return prixtot;
    }

    public void setPrixtot(float prixtot) {
        this.prixtot = prixtot;
    }
    
    

    public int getQte() {
        return qte;
    }

    public void setQte(int qte) {
        this.qte = qte;
    }

    public int getIdLigne() {
        return idLigne;
    }

    public void setIdLigne(int idLigne) {
        this.idLigne = idLigne;
    }
    
    
    
    

    
}
