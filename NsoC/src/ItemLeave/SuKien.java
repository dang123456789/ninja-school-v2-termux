/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ItemLeave;

import Menu.EventName;
import static assembly.ItemLeave.ItemEvent;
import static assembly.ItemLeave.ItemSuKienTrungThu;
import static assembly.ItemLeave.NLSKHe;
import static assembly.ItemLeave.arrItemSuKienHalloween;
import static assembly.ItemLeave.bong;
import static assembly.ItemLeave.hoahong;
import static assembly.ItemLeave.MiengDuaHau;
import static assembly.ItemLeave.NLSKVULAN;
import assembly.ItemMap;
import assembly.Mob;
import assembly.TileMap;
import io.Util;
import stream.Server;

/**
 *
 * @author Administrator
 */
public class SuKien {
    
    public static short[] arrItemSuKienNoel = new short[]{666, 667, 668};
    public static short[] arrItemSuKienTet = new short[]{638, 639, 641, 642, 674};
    
    public static void leaveItemSuKien(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int per = Util.nextInt(100);
        try {
            switch (Server.manager.event) {
                case 1: {
                    if (per < 10 && (mob3.level > 50)) {
                        im = place.LeaveItem(NLSKHe[Util.nextInt(NLSKHe.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case EventName.TRUNG_THU: {
                    if (per < 15) {
                        im = place.LeaveItem(ItemSuKienTrungThu[Util.nextInt(ItemSuKienTrungThu.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case 3: {
                    if (per < 5 && (mob3.level > 10)) {
                        im = place.LeaveItem(ItemEvent[Util.nextInt(ItemEvent.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case EventName.HALLOWEEN: {
                    if (per < 20) {
                        im = place.LeaveItem(arrItemSuKienHalloween[Util.nextInt(arrItemSuKienHalloween.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case 5: {
                    if (per < 5) {
                        im = place.LeaveItem(ItemEvent[Util.nextInt(ItemEvent.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case 6: {
                    if (per < 2 && (mob3.level > 50)) {
                        im = place.LeaveItem(hoahong[Util.nextInt(hoahong.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case EventName.DUA_HAU: {
                    if (per < 5) {
                        im = place.LeaveItem(MiengDuaHau[Util.nextInt(MiengDuaHau.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case EventName.VU_LAN: {
                    if (per < 10) {
                        im = place.LeaveItem(NLSKVULAN[Util.nextInt(NLSKVULAN.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case 9: {
                    if (per < 2 && (mob3.level > 50)) {
                        im = place.LeaveItem(bong[Util.nextInt(bong.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case EventName.SK_NOEL: {
                    if (per < 20) {
                        im = place.LeaveItem(arrItemSuKienNoel[Util.nextInt(arrItemSuKienNoel.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                case EventName.SK_TET_NGUYEN_DAN: {
                    if (per < 20) {
                        im = place.LeaveItem(arrItemSuKienTet[Util.nextInt(arrItemSuKienTet.length)], mob3.x, mob3.y, mob3.templates.type, false);
                    }
                    break;
                }
                default: {
                    break;
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (im != null) {
            im.item.quantity = 1;
            im.item.isLock = false;
            im.master = master;
        }
    }
}
