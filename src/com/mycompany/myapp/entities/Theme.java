/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

/**
 *
 * @author MSI
 */
public class Theme {
    private int idtheme ;
    private String nom ;
    private String description;

    public Theme()
    {
    }

    public Theme(int idtheme, String nom,String description) {
        this.idtheme = idtheme;
        this.nom = nom;
        this.description=description;
    }


    public Theme(String nom) {
        this.nom = nom;
    }

    public int getIdtheme() {
        return idtheme;
    }

    public void setIdtheme(int idtheme) {
        this.idtheme = idtheme;
    }



    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    @Override
    public String toString() {
        return "Theme{" + "idtheme=" + idtheme + ", nom=" + nom + ", description=" + description + '}';
    }

    









}