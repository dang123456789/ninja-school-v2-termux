/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ItemLeave;

import Item.ItemName;
import static assembly.ItemLeave.ExpXeSoi;
import static assembly.ItemLeave.TrangBiXeSoi;
import assembly.ItemMap;
import assembly.Mob;
import assembly.TileMap;
import io.Util;

/**
 *
 * @author Administrator
 */
public class VDMQ {

    public static void LeaveTrangBiXeSoiVDMQ(TileMap place, Mob mob3, int master) { // VDMQ
        ItemMap im = null;
        try {
            int random = Util.nextInt(500);
            if (random < 1) {
                int perCentArr = Util.nextInt(TrangBiXeSoi.length);
                im = place.LeaveItem(TrangBiXeSoi[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
            }
            if (random >= 2 && random <= 3) {
                im = place.LeaveItem((short) ItemName.PHAN_THAN_LENH, mob3.x, mob3.y, mob3.templates.type, false);
            }
        } catch (Exception e) {
        }
        if (im != null) {
            im.item.quantity = 1;
            im.item.isLock = false;
            im.master = master;
        }
    }

    public static void LeaveExpXeSoiVDMQ(TileMap place, Mob mob3, int master) { // VDMQ
        ItemMap im = null;
        try {
            int perCentEXP = Util.nextInt(300);
            if (perCentEXP < 5) {
                im = place.LeaveItem((short) ExpXeSoi[Util.nextInt(ExpXeSoi.length)], mob3.x, mob3.y, mob3.templates.type, false);
            }
        } catch (Exception e) {
        }
        if (im != null) {
            im.item.isLock = false;
            im.item.quantity = 1;
            im.master = master;
        }
    }

    public static void LeaveTuTinhThachVDMQ(TileMap place, Mob mob3, int master) { // VDMQ
        ItemMap im = null;
        try {
            int random = Util.nextInt(500);
            if (random < 5) {
                im = place.LeaveItem((short) 455, mob3.x, mob3.y, mob3.templates.type, false);
            } else if (random >= 10 && random <= 20) {
                im = place.LeaveItem((short) ItemName.DA_NANG_CAP, mob3.x, mob3.y, mob3.templates.type, false); // TTS
            } else if (Util.nextInt(100) <= 5) {
                im = place.LeaveItem((short) 456, mob3.x, mob3.y, mob3.templates.type, false);
            } else if (Util.nextInt(100) <= 5) {
                im = place.LeaveItem((short) 454, mob3.x, mob3.y, mob3.templates.type, false);
            } else if (random >= 10 && random <= 20) {
                im = place.LeaveItem((short) ItemName.DA_THANG_AN, mob3.x, mob3.y, mob3.templates.type, false); // TTS
            } else if (random >= 10 && random <= 20) {
                im = place.LeaveItem((short) ItemName.DA_TAI_TAO_BI_KIP, mob3.x, mob3.y, mob3.templates.type, false); // TTS
            } else if (Util.nextInt(100) <= 7) {
                im = place.LeaveItem((short) 840, mob3.x, mob3.y, mob3.templates.type, false);
            }
        } catch (Exception e) {
        }
        if (im != null) {
            im.item.isLock = false;
            im.item.quantity = 1;
            im.master = master;
        }
    }
}
