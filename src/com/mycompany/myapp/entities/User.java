/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.entities;

import java.util.Date;
import java.util.Random;

/**
 *
 * @author Pc Anis
 */
public class User {

    private int id;
    private String nom;
    private String prenom;
    private Date datedenaissance;
    private String datte;
    private String password;
    private String email;
    private String adresse;
    private Role role;

    public Role getRole() {
        return role;
    }

    public void setRole(Role role) {
        this.role = role;
    }
    private int tel;

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public Date getDatedenaissance() {
        return datedenaissance;
    }

    public void setDatedenaissance(Date datedenaissance) {
        this.datedenaissance = datedenaissance;
    }

    public String getDatte() {
        return datte;
    }

    public void setDatte(String datte) {
        this.datte = datte;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getAdresse() {
        return adresse;
    }

    public void setAdresse(String adresse) {
        this.adresse = adresse;
    }

    public int getTel() {
        return tel;
    }

    public void setTel(int tel) {
        this.tel = tel;
    }

    public Integer rondomcode() {
        Random rand = new Random();
        int randomcode = rand.nextInt(999999);
        return randomcode;
    }

    @Override
    public String toString() {
        return "User{" + "id=" + id + ", nom=" + nom + ", prenom=" + prenom + ", datedenaissance=" + datedenaissance + ", datte=" + datte + ", password=" + password + ", email=" + email + ", adresse=" + adresse + ", role=" + role + ", tel=" + tel + '}';
    }
    

}
