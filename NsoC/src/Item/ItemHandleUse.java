/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Item;

import assembly.Effect;
import assembly.Item;
import assembly.Language;
import assembly.Option;
import assembly.Player;
import io.Util;
import java.util.Calendar;
import server.Manager;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class ItemHandleUse {

    public static void RuongHuyenBiBatBaoBachNgan(Player p, Item item, byte index) {
        if (p.c.getBagNull() == 0) {
            p.c.p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
            return;
        }
        if (p.c.get().nclass == 0) {
            p.conn.sendMessageLog("Hãy nhập học để mở vật phẩm.");
            return;
        }
        byte sys2 = -1;
        int idI2;
        if (Util.nextInt(2) == 0) {
            if (p.c.gender == 0) { // đồ nữ
                if (p.c.get().level < 50 && item.id != 384 && item.id != 385) { // mở bát bảo
                    idI2 = (new short[]{171, 161, 151, 141, 131})[Util.nextInt(5)]; // đồ 4x nữ +12
                } else if (p.c.get().level < 60) {
                    idI2 = (new short[]{173, 163, 153, 143, 133})[Util.nextInt(5)]; // đồ 5x nữ +12 
                } else if (p.c.get().level < 70) {
                    idI2 = (new short[]{330, 329, 328, 327, 326})[Util.nextInt(5)]; // đồ 6x nữ
                } else {
                    idI2 = (new short[]{368, 367, 366, 365, 364})[Util.nextInt(5)]; // đồ 7x
                }
            } else if (p.c.get().level < 50 && item.id != 384 && item.id != 385) {
                idI2 = (new short[]{170, 160, 150, 140, 130})[Util.nextInt(5)]; // đồ 4x nam
            } else if (p.c.get().level < 60 && item.id != 385) {
                idI2 = (new short[]{172, 162, 152, 142, 132})[Util.nextInt(5)]; // đồ 5x nam
            } else if (p.c.get().level < 70) {
                idI2 = (new short[]{325, 323, 321, 319, 317})[Util.nextInt(5)]; // đồ 6x
            } else {
                idI2 = (new short[]{363, 361, 359, 357, 355})[Util.nextInt(5)]; // đồ 7x
            }
        } else if (Util.nextInt(2) == 1) {
            switch (p.c.get().nclass) {
                case 1:
                case 2:
                    sys2 = 1;
                    break;
                case 3:
                case 4:
                    sys2 = 2;
                    break;
                case 5:
                case 6:
                    sys2 = 3;
                    break;
                default:
                    break;
            }
            if (p.c.get().level < 50 && item.id != 384 && item.id != 385) {
                idI2 = (new short[]{97, 102, 107, 112, 117, 122})[p.c.get().nclass - 1]; // vũ khí 4x
            } else if (p.c.get().level < 60 && item.id != 385) {
                idI2 = (new short[]{98, 103, 108, 113, 118, 123})[p.c.get().nclass - 1]; // vũ khí 5x
            } else if (p.c.get().level < 70) {
                idI2 = (new short[]{331, 332, 333, 334, 335, 336})[p.c.get().nclass - 1];// vũ khí 6x
            } else {
                idI2 = (new short[]{369, 370, 371, 372, 373, 374})[p.c.get().nclass - 1]; // vũ khí 7x
            }
        } else if (p.c.get().level < 50 && item.id != 384 && item.id != 385) {
            idI2 = (new short[]{192, 187, 182, 177})[Util.nextInt(4)]; // trang bị 4x
        } else if (p.c.get().level < 60 && item.id != 385) {
            idI2 = (new short[]{193, 188, 183, 178})[Util.nextInt(4)];// trang bị 5x
        } else if (p.c.get().level < 70) {
            idI2 = (new short[]{324, 322, 320, 318})[Util.nextInt(4)];// trang bị 6x
        } else {
            idI2 = (new short[]{362, 360, 358, 356})[Util.nextInt(4)]; // trang bị 7x
        }
        Item itemup;
        if (sys2 < 0) {
            if (p.c.nclass == 1 && p.c.nclass == 2) {
                sys2 = 1;
            } else if (p.c.nclass == 3 && p.c.nclass == 4) {
                sys2 = 2;
            } else if (p.c.nclass == 5 && p.c.nclass == 6) {
                sys2 = 3;
            }
            sys2 = (byte) Util.nextInt(1, 3);
            itemup = ItemTemplate.itemDefault(idI2, sys2);
        } else {
            itemup = ItemTemplate.itemDefault(idI2);
        }
        itemup.sys = sys2;
        byte nextup = 12;
        if (item.id == 384) {
            nextup = 14;
        } else if (item.id == 385) {
            nextup = 16;
        }
        itemup.isLock = item.isLock;
        itemup.upgradeNext(nextup);
        p.c.addItemBag(true, itemup);
        p.c.removeItemBag(index, 1);
    }

    public static void KhaiThuLenh(Player p, Item item, byte index) {
        byte i;
        Item it = p.c.get().ItemMounts[4];
        if (p.c.get().isNhanban) {
            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
            return;
        }
        if (it == null) {
            p.sendAddchatYellow("Bạn Chưa Có Thú Cưỡi.");
            return;
        }
        if (it.upgrade < 99 && it.sys < 4) {
            p.sendAddchatYellow("Yêu Cầu Thú Cưỡi 5 Sao.");
            return;
        }
        for (i = 0; i < it.options.size(); ++i) {
            if (it.options.get(i).id == 57) {
                p.sendAddchatYellow("Thú Cưỡi Đã Được Khai Mở.");
                return;
            }
        }
        if (Util.nextInt(100) < 1) {
            Option op = new Option(57, Util.nextInt(50, 100));
            it.options.add(op);
            p.sendAddchatYellow("Khai Mở Thành Công");
            Manager.serverChat("Thông Báo", " Chúc Mừng " + p.c.name + " Đã Khai Mở Thú Cưỡi Thành Công ");
            p.loadMounts();
        } else {
            p.sendAddchatYellow("Khai Mở Thất Bại");
        }
        p.c.removeItemBag(index, 1);
    }

    public static void PhaLe(Player p, Item item, byte index) {
        if (p.c.isNhanban) {
            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
            return;
        }
        int checkID = item.id - 787;
        if (p.c.ItemCaiTrang[checkID] == null) {
            if (p.c.quantityItemyTotal(item.id) < 1000) {
                p.sendAddchatYellow("Bạn không đủ mảnh để ghép.");
                return;
            }
            p.c.removeItemBags(item.id, 1000);
            p.c.ItemCaiTrang[checkID] = ItemTemplate.itemDefault(ItemTemplate.checkIdCaiTrang(checkID));
            p.c.ItemCaiTrang[checkID].upgrade = 1;
            p.c.ItemCaiTrang[checkID].isLock = true;
            p.sendAddchatYellow(ItemTemplate.ItemTemplateId(p.c.ItemCaiTrang[checkID].id).name + " đã được thêm vào bộ sưu tập cải trang.");
        } else {
            if (p.c.ItemCaiTrang[checkID].upgrade >= 10) {
                p.sendAddchatYellow("Cải trang này đã đạt cấp độ tối đa, không thể nâng cấp thêm.");
                return;
            }
            if (p.c.quantityItemyTotal(item.id) < 1000) {
                p.sendAddchatYellow("Bạn không đủ " + item.id + " để nâng cấp.");
                return;
            }
            p.c.ItemCaiTrang[checkID].UpgradeCaiTrang((byte) 1);
            p.c.removeItemBags(item.id, 1000);
            p.sendAddchatYellow(ItemTemplate.ItemTemplateId(p.c.ItemCaiTrang[checkID].id).name + " đã được nâng cấp.");
        }
    }

    public static void HoaTuyet(Player p, Item item, byte index) {
        if (p.c.isNhanban) {
            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
            return;
        }
        int checkID = item.id - 775;
        if (p.c.ItemCaiTrang[checkID] == null) {
            if (p.c.quantityItemyTotal(item.id) < 1000) {
                p.sendAddchatYellow("Bạn không đủ mảnh để ghép.");
                return;
            }
            p.c.removeItemBags(item.id, 1000);
            p.c.ItemCaiTrang[checkID] = ItemTemplate.itemDefault(ItemTemplate.checkIdCaiTrang(checkID));
            p.c.ItemCaiTrang[checkID].upgrade = 1;
            p.c.ItemCaiTrang[checkID].isLock = true;
            p.sendAddchatYellow(ItemTemplate.ItemTemplateId(p.c.ItemCaiTrang[checkID].id).name + " đã được thêm vào bộ sưu tập cải trang.");
        } else {
            if (p.c.ItemCaiTrang[checkID].upgrade >= 10) {
                p.sendAddchatYellow("Cải trang này đã đạt cấp độ tối đa, không thể nâng cấp thêm.");
                return;
            }
            if (p.c.quantityItemyTotal(item.id) < 1000) {
                p.sendAddchatYellow("Bạn không đủ " + item.id + " để nâng cấp.");
                return;
            }
            p.c.ItemCaiTrang[checkID].UpgradeCaiTrang((byte) 1);
            p.c.removeItemBags(item.id, 1000);
            p.sendAddchatYellow(ItemTemplate.ItemTemplateId(p.c.ItemCaiTrang[checkID].id).name + " đã được nâng cấp.");
        }
    }

    public static void NhamThach(Player p, Item item, byte index) {
        if (p.c.isNhanban) {
            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
            return;
        }
        int checkID = item.id - 787;
        if (p.c.ItemCaiTrang[checkID] == null) {
            if (p.c.quantityItemyTotal(item.id) < 1000) {
                p.sendAddchatYellow("Bạn không đủ mảnh để ghép.");
                return;
            }
            p.c.removeItemBags(item.id, 1000);
            p.c.ItemCaiTrang[checkID] = ItemTemplate.itemDefault(ItemTemplate.checkIdCaiTrang(checkID));
            p.c.ItemCaiTrang[checkID].upgrade = 1;
            p.c.ItemCaiTrang[checkID].isLock = true;
            p.sendAddchatYellow(ItemTemplate.ItemTemplateId(p.c.ItemCaiTrang[checkID].id).name + " đã được thêm vào bộ sưu tập cải trang.");
        } else {
            if (p.c.ItemCaiTrang[checkID].upgrade >= 10) {
                p.sendAddchatYellow("Cải trang này đã đạt cấp độ tối đa, không thể nâng cấp thêm.");
                return;
            }
            if (p.c.quantityItemyTotal(item.id) < 1000) {
                p.sendAddchatYellow("Bạn không đủ " + item.id + " để nâng cấp.");
                return;
            }
            p.c.ItemCaiTrang[checkID].UpgradeCaiTrang((byte) 1);
            p.c.removeItemBags(item.id, 1000);
            p.sendAddchatYellow(ItemTemplate.ItemTemplateId(p.c.ItemCaiTrang[checkID].id).name + " đã được nâng cấp.");
        }
    }

    public static void ThiLuyenThiep(Player p, Item item, byte index) {

        final Effect eff = p.c.get().getEffId(34);
//        if (p.c.setmaxtnp == 1) {
//            p.sendAddchatYellow("Chỉ sử dụng được 1 cái 1 ngày");
//            return;
//        }
        if (eff != null) {
            p.sendAddchatYellow("Chỉ sử dụng được 1 cái 1 lần");
            return;
        } else {
            p.setEffect(34, 0, 86400000, 2);
//            p.c.setmaxtnp += 1; 
        }
        p.c.removeItemBag(index, 1);
    }
}
