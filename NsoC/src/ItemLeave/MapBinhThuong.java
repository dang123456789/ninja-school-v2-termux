/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ItemLeave;

import static assembly.ItemLeave.ItemTrangBi3x;
import static assembly.ItemLeave.ItemTrangBi4x;
import static assembly.ItemLeave.ItemTrangBi5x;
import static assembly.ItemLeave.ItemTrangBi6x;
import static assembly.ItemLeave.ItemTrangBi7x;
import static assembly.ItemLeave.ItemTrangBi8x;
import static assembly.ItemLeave.arrItemmapngoai;
import assembly.ItemMap;
import assembly.Mob;
import assembly.TileMap;
import io.Util;

/**
 *
 * @author Administrator
 */
public class MapBinhThuong {

    public static void leaveItemmapngoai(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int percent = Util.nextInt(arrItemmapngoai.length);
        try {
            if (arrItemmapngoai[percent] != -1) {
                switch (arrItemmapngoai[percent]) {
                    case 10000: { // rơi hp theo level
                        if (mob3.level < 30) {
                            im = place.LeaveItem((short) 14, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 30 && mob3.level < 50) {
                            im = place.LeaveItem((short) 15, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 50 && mob3.level < 70) {
                            im = place.LeaveItem((short) 16, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 70) {
                            im = place.LeaveItem((short) 17, mob3.x, mob3.y, mob3.templates.type, false);
                        }
                        break;
                    }
                    case 10001: { // rơi mp theo level
                        if (mob3.level < 30) {
                            im = place.LeaveItem((short) 19, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 30 && mob3.level < 50) {
                            im = place.LeaveItem((short) 20, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 50 && mob3.level < 70) {
                            im = place.LeaveItem((short) 21, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 70) {
                            im = place.LeaveItem((short) 22, mob3.x, mob3.y, mob3.templates.type, false);
                        }
                        break;
                    }
                    case 10002: { // rơi da theo level
                        if (mob3.level < 30) {
                            im = place.LeaveItem((short) 4, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 30 && mob3.level < 50) {
                            im = place.LeaveItem((short) 5, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 50 && mob3.level < 70) {
                            im = place.LeaveItem((short) 6, mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 70) {
                            im = place.LeaveItem((short) 7, mob3.x, mob3.y, mob3.templates.type, false);
                        }
                        break;
                    }
                    case 10003: {
                        if (mob3.level >= 30 && mob3.level < 40) {
                            int perCentArr = Util.nextInt(ItemTrangBi3x.length);
                            im = place.LeaveItem(ItemTrangBi3x[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 40 && mob3.level < 50) {
                            int perCentArr = Util.nextInt(ItemTrangBi4x.length);
                            im = place.LeaveItem(ItemTrangBi4x[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 50 && mob3.level < 60) {
                            int perCentArr = Util.nextInt(ItemTrangBi5x.length);
                            im = place.LeaveItem(ItemTrangBi5x[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 60 && mob3.level < 70) {
                            int perCentArr = Util.nextInt(ItemTrangBi6x.length);
                            im = place.LeaveItem(ItemTrangBi6x[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 70 && mob3.level < 80) {
                            int perCentArr = Util.nextInt(ItemTrangBi7x.length);
                            im = place.LeaveItem(ItemTrangBi7x[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
                        } else if (mob3.level >= 80) {
                            int perCentArr = Util.nextInt(ItemTrangBi8x.length);
                            im = place.LeaveItem(ItemTrangBi8x[perCentArr], mob3.x, mob3.y, mob3.templates.type, false);
                        }
                        break;
                    }
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
