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
public class Facture {
    private int id;
    private float montant_totale;
    private String mode_paiement;
    private int user;
    private Date date_fac;

    public Facture() {
    }

    public Facture(int id, float montant_totale, String mode_paiement, int user, Date date_fac) {
        this.id = id;
        this.montant_totale = montant_totale;
        this.mode_paiement = mode_paiement;
        this.user = user;
        this.date_fac = date_fac;
    }

    public Facture(float montant_totale, String mode_paiement, int user, Date date_fac) {
        this.montant_totale = montant_totale;
        this.mode_paiement = mode_paiement;
        this.user = user;
        this.date_fac = date_fac;
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

    public String getMode_paiement() {
        return mode_paiement;
    }

    public void setMode_paiement(String mode_paiement) {
        this.mode_paiement = mode_paiement;
    }

    public int getUser() {
        return user;
    }

    public void setUser(int user) {
        this.user = user;
    }

    public Date getDate_fac() {
        return date_fac;
    }

    public void setDate_fac(Date date_fac) {
        this.date_fac = date_fac;
    }

    @Override
    public String toString() {
        return "Facture{" + "id=" + id + ", montant_totale=" + montant_totale + ", mode_paiement=" + mode_paiement + ", user=" + user + ", date_fac=" + date_fac + '}';
    }
    
    
    
}
