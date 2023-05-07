/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

import java.util.Date;

/**
 *
 * @author Pc Anis
 */
public class Event {
     private int id;
    private String Nomevent;
    private String description;
    private String lieu;
    private float prix;
    private Date date_evenement;
    private Theme theme;
    private int user;
    private String image;
    private String nom;
    private int nbrparticipant;

    public Event() {
    }

    public Event(String Nomevent, String description, String lieu, float prix) {
        this.Nomevent = Nomevent;
        this.description = description;
        this.lieu = lieu;
        this.prix = prix;
    }
    

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNomevent() {
        return Nomevent;
    }

    public void setNomevent(String Nomevent) {
        this.Nomevent = Nomevent;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getLieu() {
        return lieu;
    }

    public void setLieu(String lieu) {
        this.lieu = lieu;
    }

    public float getPrix() {
        return prix;
    }

    public void setPrix(float prix) {
        this.prix = prix;
    }

    public Date getDate_evenement() {
        return date_evenement;
    }

    public void setDate_evenement(Date date_evenement) {
        this.date_evenement = date_evenement;
    }

    public Theme getTheme() {
        return theme;
    }

    public void setTheme(Theme theme) {
        this.theme = theme;
    }

    public int getUser() {
        return user;
    }

    public void setUser(int user) {
        this.user = user;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public int getNbrparticipant() {
        return nbrparticipant;
    }

    public void setNbrparticipant(int nbrparticipant) {
        this.nbrparticipant = nbrparticipant;
    }

    @Override
    public String toString() {
        return "Event{" + "id=" + id + ", Nomevent=" + Nomevent + ", description=" + description + ", lieu=" + lieu + ", prix=" + prix + ", date_evenement=" + date_evenement + ", theme=" + theme + ", user=" + user + ", image=" + image + ", nom=" + nom + ", nbrparticipant=" + nbrparticipant + '}';
    }
    
    
}
