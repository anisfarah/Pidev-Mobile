/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

import java.util.Date;

/**
 *
 * @author Dell 6540
 */
public class Reclamation {
    private int idRec;
    private String contenu;
    private Date dateRec;
    private String etat;
    private String img;
    private int idType;
    private int idUser;
    private int reponserecs;

    public Reclamation() {
    }

    public Reclamation(int idRec, String contenu, Date dateRec, String etat, String img, int idType, int idUser, int reponserecs) {
        this.idRec = idRec;
        this.contenu = contenu;
        this.dateRec = dateRec;
        this.etat = etat;
        this.img = img;
        this.idType = idType;
        this.idUser = idUser;
        this.reponserecs = reponserecs;
    }

    public Reclamation(String contenu, Date dateRec, String etat, String img, int idType, int idUser, int reponserecs) {
        this.contenu = contenu;
        this.dateRec = dateRec;
        this.etat = etat;
        this.img = img;
        this.idType = idType;
        this.idUser = idUser;
        this.reponserecs = reponserecs;
    }

    public int getIdRec() {
        return idRec;
    }

    public void setIdRec(int idRec) {
        this.idRec = idRec;
    }

    public String getContenu() {
        return contenu;
    }

    public void setContenu(String contenu) {
        this.contenu = contenu;
    }

    public Date getDateRec() {
        return dateRec;
    }

    public void setDateRec(Date dateRec) {
        this.dateRec = dateRec;
    }

    public String getEtat() {
        return etat;
    }

    public void setEtat(String etat) {
        this.etat = etat;
    }

    public String getImg() {
        return img;
    }

    public void setImg(String img) {
        this.img = img;
    }

    public int getIdType() {
        return idType;
    }

    public void setIdType(int idType) {
        this.idType = idType;
    }

    public int getIdUser() {
        return idUser;
    }

    public void setIdUser(int idUser) {
        this.idUser = idUser;
    }

    public int getReponserecs() {
        return reponserecs;
    }

    public void setReponserecs(int reponserecs) {
        this.reponserecs = reponserecs;
    }
            
                   
}