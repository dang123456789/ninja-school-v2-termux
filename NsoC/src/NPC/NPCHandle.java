/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package NPC;

import assembly.Player;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class NPCHandle {

    public static void doiGiayVun(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                p.c.removeItemBags(251, 250);
                p.c.addItemBag(false, ItemTemplate.itemDefault(252, false));
                break;
            }
            case 1: {
                p.c.removeItemBags(251, 300);
                p.c.addItemBag(false, ItemTemplate.itemDefault(253, false));
                break;
            }
        }
    }
}
