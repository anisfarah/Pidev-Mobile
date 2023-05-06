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
public class Promo {
    
    private int id ;
    private String code  ;
    private double reduction ;
    private Date date_debut ;
    private Date date_fin ;

    public Promo() {
    }

    public Promo(int id, String code, double reduction, Date date_debut, Date date_fin) {
        this.id = id;
        this.code = code;
        this.reduction = reduction;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
    }
    
     public Promo(String code, double reduction) {
      
        this.code = code;
        this.reduction = reduction;
        
    }

    public Promo(String code, double reduction, Date date_debut, Date date_fin) {
        this.code = code;
        this.reduction = reduction;
        this.date_debut = date_debut;
        this.date_fin = date_fin;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code;
    }

    public Double getReduction() {
        return reduction;
    }

    public void setReduction(double reduction) {
        this.reduction = reduction;
    }

    public Date getDate_debut() {
        return date_debut;
    }

    public void setDate_debut(Date date_debut) {
        this.date_debut = date_debut;
    }

    public Date getDate_fin() {
        return date_fin;
    }

    public void setDate_fin(Date date_fin) {
        this.date_fin = date_fin;
    }

    @Override
    public String toString() {
        return "Promo{" + "id=" + id + ", code=" + code 
                + ", reduction=" + reduction + ", date_debut=" + date_debut 
                + ", date_fin=" + date_fin + '}';
    }
    
    
    
    
}
