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
public class Participation {
    
    private int id_partipation;
    private Event event;
    private User user;

    public Participation() {
    }

    public Participation(int id_partipation, Event event, User user) {
        this.id_partipation = id_partipation;
        this.event = event;
        this.user = user;
    }

    public Participation(Event event, User user) {
        this.event = event;
        this.user = user;
    }

    public int getId_partipation() {
        return id_partipation;
    }

    public void setId_partipation(int id_partipation) {
        this.id_partipation = id_partipation;
    }

    public Event getEvent() {
        return event;
    }

    public void setEvent(Event event) {
        this.event = event;
    }

    public User getUser() {
        return user;
    }

    public void setUser(User user) {
        this.user = user;
    }

    @Override
    public String toString() {
        return "Participation{" + "id_partipation=" + id_partipation + ", event=" + event + ", user=" + user + '}';
    }
    
 
}
