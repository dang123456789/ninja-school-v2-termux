/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package ItemLeave;

import static assembly.ItemLeave.ItemBOSSSuKien;
import static assembly.ItemLeave.ItemBOSSThuong;
import static assembly.ItemLeave.ItemBOSSVDMQ;
import static assembly.ItemLeave.SVC10x;
import static assembly.ItemLeave.SVC12x;
import static assembly.ItemLeave.ItemBOSSLC1;
import static assembly.ItemLeave.VuKhi5x;
import static assembly.ItemLeave.VuKhi6x;
import static assembly.ItemLeave.VuKhi7x;
import static assembly.ItemLeave.VuKhi8x;
import static assembly.ItemLeave.VuKhi9x;
import assembly.ItemMap;
import assembly.Mob;
import assembly.TileMap;
import io.Util;

/**
 *
 * @author Administrator
 */
public class Boss {

    public static void ItemBossThuong(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int random = Util.nextInt(50);
        int i;
        try {
            // random vũ khí
            if (random < 4) {
                for (i = 0; i < VuKhi5x.length; i++) {
                    im = place.LeaveItem(VuKhi5x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }
            } else if (random < 3) {
                for (i = 0; i < VuKhi6x.length; i++) {
                    im = place.LeaveItem(VuKhi6x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }

            } else if (random < 2) {
                for (i = 0; i < VuKhi7x.length; i++) {
                    im = place.LeaveItem((short) VuKhi7x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }

            } else if (random < 1) {
                for (i = 0; i < VuKhi8x.length; i++) {
                    im = place.LeaveItem((short) VuKhi8x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }
            }
            // Item Mặc Định
            for (i = 0; i < ItemBOSSThuong.length; i++) {
                im = place.LeaveItem(ItemBOSSThuong[i], mob3.x, mob3.y, mob3.templates.type, true);
                if (im != null) {
                    im.item.quantity = 1;
                    im.item.isLock = false;
                    im.master = master;
                }
            }
        } catch (Exception e) {
        }
    }
// Set Item Vào Boss
    
     public static void ItemBossLC(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int i;
        int random = Util.nextInt(100);
        try {
           
            if (random < 2) {
                for (i = 0; i < VuKhi8x.length; i++) {
                    im = place.LeaveItem((short) VuKhi8x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(6, 10));
                        im.master = master;
                    }
                }
            }
            if (random < 2) {
                for (i = 0; i < VuKhi9x.length; i++) {
                    im = place.LeaveItem((short) VuKhi9x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            if (random < 1) {
                for (i = 0; i < SVC10x.length; i++) {
                    im = place.LeaveItem(SVC10x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            if (random < 2) {
                for (i = 0; i < SVC12x.length; i++) {
                    im = place.LeaveItem(SVC12x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            // Item Mặc Định
            for (i = 0; i < ItemBOSSLC1.length; i++) {
                im = place.LeaveItem(ItemBOSSLC1[i], mob3.x, mob3.y, mob3.templates.type, true);
                if (im != null) {
                    im.item.quantity = 1;
                    im.item.isLock = false;
                    im.master = master;
                }
            }
        } catch (Exception e) {
        }
    }

    public static void ItemBossVDMQ(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int i;
        int random = Util.nextInt(100);
        try {
            if (random < 5) {
                for (i = 0; i < VuKhi5x.length; i++) {
                    im = place.LeaveItem(VuKhi5x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }
            }

            if (random < 4) {
                for (i = 0; i < VuKhi6x.length; i++) {
                    im = place.LeaveItem(VuKhi6x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }

            }
            if (random < 3) {
                for (i = 0; i < VuKhi7x.length; i++) {
                    im = place.LeaveItem((short) VuKhi7x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }

            }
            if (random < 2) {
                for (i = 0; i < VuKhi8x.length; i++) {
                    im = place.LeaveItem((short) VuKhi8x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }
            }
            if (random < 1) {
                for (i = 0; i < VuKhi9x.length; i++) {
                    im = place.LeaveItem(VuKhi9x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.item.upgradeNext((byte) Util.nextInt(8, 13));
                        im.master = master;
                    }
                }
            }
            if (random < 2) {
                for (i = 0; i < SVC10x.length; i++) {
                    im = place.LeaveItem(SVC10x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            // Item Mặc Định
            for (i = 0; i < ItemBOSSVDMQ.length; i++) {
                im = place.LeaveItem(ItemBOSSVDMQ[i], mob3.x, mob3.y, mob3.templates.type, true);
                if (im != null) {
                    im.item.quantity = 1;
                    im.item.isLock = false;
                    im.master = master;
                }
            }
        } catch (Exception e) {
        }
    }

    public static void ItemBossSuKien(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int i;
        int random = Util.nextInt(100);
        try {
            if (random < 10) {
                for (i = 0; i < VuKhi9x.length; i++) {
                    im = place.LeaveItem(VuKhi9x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            if (random < 10) {
                for (i = 0; i < SVC10x.length; i++) {
                    im = place.LeaveItem(SVC10x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            // Item Mặc Định
            for (i = 0; i < ItemBOSSSuKien.length; i++) {
                im = place.LeaveItem(ItemBOSSSuKien[i], mob3.x, mob3.y, mob3.templates.type, true);
                if (im != null) {
                    im.item.quantity = 1;
                    im.item.isLock = false;
                    im.master = master;
                }
            }
        } catch (Exception e) {
        }
    }
    
    

    public static void ItemBossTruyenThuyet(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        int i;
        int random = Util.nextInt(100);
        try {
            if (random < 10) {
                for (i = 0; i < VuKhi9x.length; i++) {
                    im = place.LeaveItem(VuKhi9x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            if (random < 10) {
                for (i = 0; i < SVC10x.length; i++) {
                    im = place.LeaveItem(SVC10x[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
            // Item Mặc Định
            for (i = 0; i < ItemBOSSSuKien.length; i++) {
                im = place.LeaveItem(ItemBOSSSuKien[i], mob3.x, mob3.y, mob3.templates.type, true);
                if (im != null) {
                    im.item.quantity = 1;
                    im.item.isLock = false;
                    im.master = master;
                }
            }
        } catch (Exception e) {
        }
    }
}
