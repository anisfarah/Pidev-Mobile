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
public class LigneFacture {
    private int id_ligne_fac;
    private Facture id_facture;
    private Livre id_livre;
    private int id_user;
    private float mnt;
    private int qte;

    public LigneFacture() {
    }

    public LigneFacture(int id_ligne_fac, Facture id_facture, Livre id_livre, int id_user, float mnt, int qte) {
        this.id_ligne_fac = id_ligne_fac;
        this.id_facture = id_facture;
        this.id_livre = id_livre;
        this.id_user = id_user;
        this.mnt = mnt;
        this.qte = qte;
    }

    public LigneFacture(Facture id_facture, Livre id_livre, int id_user, float mnt, int qte) {
        this.id_facture = id_facture;
        this.id_livre = id_livre;
        this.id_user = id_user;
        this.mnt = mnt;
        this.qte = qte;
    }

    public int getId_ligne_fac() {
        return id_ligne_fac;
    }

    public void setId_ligne_fac(int id_ligne_fac) {
        this.id_ligne_fac = id_ligne_fac;
    }

    public Facture getId_facture() {
        return id_facture;
    }

    public void setId_facture(Facture id_facture) {
        this.id_facture = id_facture;
    }

    public Livre getId_livre() {
        return id_livre;
    }

    public void setId_livre(Livre id_livre) {
        this.id_livre = id_livre;
    }

    public int getId_user() {
        return id_user;
    }

    public void setId_user(int id_user) {
        this.id_user = id_user;
    }

    public float getMnt() {
        return mnt;
    }

    public void setMnt(float mnt) {
        this.mnt = mnt;
    }

    public int getQte() {
        return qte;
    }

    public void setQte(int qte) {
        this.qte = qte;
    }

    @Override
    public String toString() {
        return "LigneFacture{" + "id_ligne_fac=" + id_ligne_fac + ", id_facture=" + id_facture + ", id_livre=" + id_livre + ", id_user=" + id_user + ", mnt=" + mnt + ", qte=" + qte + '}';
    }
    
    
    
}
