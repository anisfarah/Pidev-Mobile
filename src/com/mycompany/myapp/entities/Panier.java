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
public class Panier {
    private int id;
    private float montant_totale;
    private int qte;
    private int user;

    public Panier() {
    }

    public Panier(int id, float montant_totale, int qte, int user) {
        this.id = id;
        this.montant_totale = montant_totale;
        this.qte = qte;
        this.user = user;
    }

    public Panier(float montant_totale, int qte, int user) {
        this.montant_totale = montant_totale;
        this.qte = qte;
        this.user = user;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public float getMontant_totale() {
        return montant_totale;
    }

    public void setMontant_totale(float montant_totale) {
        this.montant_totale = montant_totale;
    }

    public int getQte() {
        return qte;
    }

    public void setQte(int qte) {
        this.qte = qte;
    }

    public int getUser() {
        return user;
    }

    public void setUser(int user) {
        this.user = user;
    }

    @Override
    public String toString() {
        return "Panier{" + "id=" + id + ", montant_totale=" + montant_totale + ", qte=" + qte + ", user=" + user + '}';
    }
    
    
    
}
