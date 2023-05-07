/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.myapp.gui;

import com.codename1.io.ConnectionRequest;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.events.ActionListener;
import com.mycompany.myapp.entities.Event;
import com.mycompany.myapp.entities.Participation;
import com.mycompany.myapp.services.ServiceEvent;
import com.mycompany.myapp.utils.Statics;

/**
 *
 * @author Pc Anis
 */
public class ServiceParticipation {
    public static ServiceParticipation instance = null;
    public boolean resultOK;
    private ConnectionRequest req;

    public ServiceParticipation() {
        req = new ConnectionRequest();
    }

    public static ServiceParticipation getInstance() {
        if (instance == null) {
            instance = new ServiceParticipation();
        }
        return instance;
    }
    
    public boolean addParticipation(Event e) {

        String url = Statics.BASE_URL + "/participation/participerJSON/" + e.getId();

        req.setUrl(url);
        req.setPost(false);

        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return resultOK;
    }
    
}
