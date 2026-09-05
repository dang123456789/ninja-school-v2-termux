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
public class LangTruyenThuyet {

    public static void LeaveExpXeSoiLangTruyenThuyet(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        try {
            int perCentEXP = Util.nextInt(100);
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

    public static void LeaveTrangBiXeSoiLangTruyenThuyet(TileMap place, Mob mob3, int master) { // Map Mới
        ItemMap im = null;
        try {
            int perCentTB = Util.nextInt(400);
            if (perCentTB < 5) {
                int perCentArr = Util.nextInt(TrangBiXeSoi.length);
                im = place.LeaveItem(TrangBiXeSoi[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
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

    public static void LeaveTuTinhThachLangTruyenThuyet(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        try {
            int random = Util.nextInt(120);
            if (random < 10) {
                im = place.LeaveItem((short) 455, mob3.x, mob3.y, mob3.templates.type, false); // TTS
            } else if (random >= 10 && random <= 17) {
                im = place.LeaveItem((short) ItemName.DA_NANG_CAP, mob3.x, mob3.y, mob3.templates.type, false); 
            } else if (random >= 10 && random <= 10) {
                im = place.LeaveItem((short) 456, mob3.x, mob3.y, mob3.templates.type, false); // ttt
            } else if (Util.nextInt(100) <= 7) {
                im = place.LeaveItem((short) 454, mob3.x, mob3.y, mob3.templates.type, false);
            } else if (Util.nextInt(100) <= 1) {
                im = place.LeaveItem((short) 457, mob3.x, mob3.y, mob3.templates.type, false);
            } else if (random >= 10 && random <= 17) {
                im = place.LeaveItem((short) ItemName.DA_THANG_AN, mob3.x, mob3.y, mob3.templates.type, false); // TTS
            } else if (random >= 10 && random <= 17) {
                im = place.LeaveItem((short) ItemName.DA_TAI_TAO_BI_KIP, mob3.x, mob3.y, mob3.templates.type, false); // TTS
            } else if (Util.nextInt(100) <= 7) {
                im = place.LeaveItem((short) 840, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 648, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 649, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 650, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 651, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 662, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 1006, mob3.x, mob3.y, mob3.templates.type, false);
                } else if (Util.nextInt(100) <= 10) {
                im = place.LeaveItem((short) 956, mob3.x, mob3.y, mob3.templates.type, false);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        if (im != null) {
            im.item.isLock = false;
            im.item.quantity = 1;
            im.master = master;
        }
    }
}
