package Item;

import History.LichSu;
import Menu.EventName;
import assembly.Item;
import assembly.Language;
import assembly.Option;
import assembly.Player;
import static assembly.UseItem.HanSuDung;
import static assembly.UseItem.HanSuDungNew;
import io.Util;
import server.Manager;
import server.Service;
import stream.Server;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class ItemHandleUseEvent {

    public static void Banh(Player p, Item item, byte index) {
        if (Server.manager.event != 2) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.level < 20) {
            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
            return;
        }
        p.updateExp(1000000L);
        p.c.removeItemBag(index, 1);
    }

    public static void HopBanhThuong(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);

        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.updateExp(10000000);
        if (Util.nextInt(10) < 5) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        }
        int itemID = RandomItem.HOP_BANH_THUONG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        p.c.addItemBag(true, itm);
    }

    public static void HopBanhThuongHang(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);

        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 1;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(1000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(1001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(1002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(1003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.HOP_BANH_THUONG_HANG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {

                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(82, Util.nextInt(1000, 2000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }

    public static void LongDen(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != 2) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 1;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
            return;
        }
        int itemID = RandomItem.LONG_DEN.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {
            if (Util.percent(500, 1)) {
                itm.options.add(new Option(58, 20));
                itm.options.add(new Option(92, 100));
                itm.options.add(new Option(94, 150));
                itm.options.add(new Option(121, 30));
                itm.options.add(new Option(84, 100));
                itm.options.add(new Option(80, 500));
                itm.options.add(new Option(120, 1000));
                itm.options.add(new Option(127, 10));
                itm.options.add(new Option(128, 10));
                itm.options.add(new Option(129, 10));
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            } else {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
            if (Util.percent(500, 1)) {
                itm.options.add(new Option(82, 5000));
                itm.options.add(new Option(94, 100));
                itm.options.add(new Option(80, 200));
                itm.options.add(new Option(92, 100));
                itm.options.add(new Option(58, 10));
                itm.options.add(new Option(127, 10));
                itm.options.add(new Option(128, 10));
                itm.options.add(new Option(129, 10));
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            } else {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        }

        if (itemID == ItemName.BACH_HO || itemID == ItemName.LAN_SU_VU || itemID == ItemName.XICH_NHAN_NGAN_LANG || itemID == ItemName.XE_MAY || itemID == ItemName.HARLEY_DAVIDSON) {
            if (Util.percent(100, 1)) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
            } else {
                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                itm.options.add(new Option(127, 10));
                itm.options.add(new Option(128, 10));
                itm.options.add(new Option(129, 10));
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            }
        }
        if (itemID == ItemName.MAT_NA_VEGETA || itemID == ItemName.MAT_NA_KUNOICHI) {
            if (Util.percent(500, 1)) {
                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                itm.options.add(new Option(58, 20));
                itm.options.add(new Option(92, 100));
                itm.options.add(new Option(94, 100));
                itm.options.add(new Option(5000, 82));
                itm.options.add(new Option(127, 10));
                itm.options.add(new Option(128, 10));
                itm.options.add(new Option(129, 10));
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            } else {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }

    public static void HomMayMan(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.HALLOWEEN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }

        p.c.removeItemBags(819, 1);
        int itemID = RandomItem.HOM_MAY_MAN.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        itm.isLock = item.isLock;
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN) {
            if (Util.percent(500, 1)) {
                itm.options.add(new Option(0, 200));
                itm.options.add(new Option(1, 200));
                itm.options.add(new Option(58, 20));
                itm.options.add(new Option(8, 150));
                itm.options.add(new Option(9, 150));
                itm.options.add(new Option(94, 100));
                itm.options.add(new Option(92, 100));
                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            } else {
                int HSD = HanSuDungNew[Util.nextInt(HanSuDungNew.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, 5000));
                itm.options.add(new Option(81, 300));
                itm.options.add(new Option(84, 150));
                itm.options.add(new Option(86, 150));
                itm.options.add(new Option(91, 100));
                itm.options.add(new Option(98, 20));
                itm.options.add(new Option(74, 300));
            }
        }
        if (itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_INU) {
            if (Util.percent(1000, 1)) {
                itm.options.add(new Option(82, 5000));
                itm.options.add(new Option(81, 300));
                itm.options.add(new Option(84, 150));
                itm.options.add(new Option(86, 150));
                itm.options.add(new Option(91, 100));
                itm.options.add(new Option(98, 20));
                itm.options.add(new Option(74, 300));
                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            } else {
                int HSD = HanSuDungNew[Util.nextInt(HanSuDungNew.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, 5000));
                itm.options.add(new Option(81, 300));
                itm.options.add(new Option(84, 150));
                itm.options.add(new Option(86, 150));
                itm.options.add(new Option(91, 100));
                itm.options.add(new Option(98, 20));
                itm.options.add(new Option(74, 300));
            }
        }
        if (itemID == 801 || itemID == 802 || itemID == 803) {
            if (Util.percent(10, 49)) {
                int HSD = HanSuDungNew[Util.nextInt(HanSuDungNew.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
            } else {
                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            }
        }
        if (itemID == ItemName.HAKAIRO_YOROI) {
            if (Util.percent(1000, 10)) {
                itm.options.add(new Option(58, 20));
                itm.options.add(new Option(87, 5000));
                itm.options.add(new Option(94, 100));
                itm.options.add(new Option(92, 100));
                itm.options.add(new Option(67, 100));
                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
            } else {
                int HSD = HanSuDungNew[Util.nextInt(HanSuDungNew.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, 20));
                itm.options.add(new Option(87, 5000));
                itm.options.add(new Option(94, 100));
                itm.options.add(new Option(92, 100));
                itm.options.add(new Option(67, 100));
            }
        }

        p.c.addItemBag(true, itm);
    }
    
    public static void DuaHauDai(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.DUA_HAU) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
//            Service.AutoSaveData();
            return;
        }
        p.c.eventPoint += 1;
        p.c.removeItemBag(index, 1);
//        Service.AutoSaveData();
        p.updateExp(10000000);
        if (Util.nextInt(10) < 5) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(22, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        }  else if (Util.percent(10000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(10001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(10002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(10003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.DUA_HAU_DAI.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        p.c.addItemBag(true, itm);
    }
    
    public static void DuaHauTron(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.DUA_HAU) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
//            Service.AutoSaveData();
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 2;
//        Service.AutoSaveData();
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(20, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(10000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(10001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(10002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(10003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.DUA_HAU_TRON.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {
//            if (Util.percent(120, 1)) {
//                itm.options.add(new Option(58, 20));
//                itm.options.add(new Option(92, 100));
//                itm.options.add(new Option(94, 150));
//                itm.options.add(new Option(121, 30));
//                itm.options.add(new Option(84, 100));
//                itm.options.add(new Option(80, 500));
//                itm.options.add(new Option(120, 1000));
//                itm.options.add(new Option(127, 10));
//                itm.options.add(new Option(128, 10));
//                itm.options.add(new Option(129, 10));
//                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
//            } else {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
//            }
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
//            if (Util.percent(120, 1)) {
//                itm.options.add(new Option(82, 5000));
//                itm.options.add(new Option(94, 100));
//                itm.options.add(new Option(80, 200));
//                itm.options.add(new Option(92, 100));
//                itm.options.add(new Option(58, 10));
//                itm.options.add(new Option(127, 10));
//                itm.options.add(new Option(128, 10));
//                itm.options.add(new Option(129, 10));
//                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
//            } else {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
//            }
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
//            if (Util.percent(120, 1)) {
//                Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
//                itm.options.add(new Option(58, 20));
//                itm.options.add(new Option(92, 100));
//                itm.options.add(new Option(94, 100));
//                itm.options.add(new Option(5000, 82));
//                itm.options.add(new Option(127, 10));
//                itm.options.add(new Option(128, 10));
//                itm.options.add(new Option(129, 10));
//                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
//            } else {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
//            }
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }
    public static void BoSenTrang(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.VU_LAN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.updateExp(10000000);
        if (Util.nextInt(10) < 5) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        }
        int itemID = RandomItem.BO_SEN_TRANG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        p.c.addItemBag(true, itm);
    }

    public static void BoSenHong(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.VU_LAN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 1;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(1000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(1001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(1002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(1003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.BO_SEN_HONG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {

                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(82, Util.nextInt(1000, 2000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }
    
    public static void KeoTao(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.HALLOWEEN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.updateExp(10000000);
        if (Util.nextInt(10) < 5) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        }
        int itemID = RandomItem.BO_SEN_TRANG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        p.c.addItemBag(true, itm);
    }

    public static void HopMaQuy(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.HALLOWEEN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        if (p.c.quantityItemyTotal(ItemName.CHIA_KHOA) < 1) {
            p.sendAddchatYellow("Bạn không đủ chìa khoá.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.removeItemBags(818, 1);
        p.c.eventPoint += 1;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(1000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(1001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(1002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(1003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.BO_SEN_HONG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {

                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(82, Util.nextInt(1000, 2000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }
    public static void BanhKhucDauTay(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.SK_NOEL) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.updateExp(10000000);
        if (Util.nextInt(10) < 5) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        }
        int itemID = RandomItem.BANH_KHUC_CAY_DAU_TAY.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        p.c.addItemBag(true, itm);
    }

    public static void BanhKhucChocolate(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.SK_NOEL) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 1;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(1000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(1001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(1002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(1003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.BANH_KHUC_CAY_CHOCOLATE.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {

                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(82, Util.nextInt(1000, 2000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }
    
    public static void BanhTet(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.SK_TET_NGUYEN_DAN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.updateExp(10000000);
        if (Util.nextInt(10) < 5) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        }
        int itemID = RandomItem.BANH_TET.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
            }
        p.c.addItemBag(true, itm);
    }

    public static void BanhChung(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.SK_TET_NGUYEN_DAN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 1;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(1000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(1001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(1002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(1003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.BANH_CHUNG.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {

                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(82, Util.nextInt(1000, 2000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }
    
    public static void TrangPhao(Player p, Item item, byte index) {
        ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
        if (Server.manager.event != EventName.SK_TET_NGUYEN_DAN) {
            p.sendAddchatYellow(Language.END_EVENT);
            return;
        }
        if (p.c.getBagNull() == 0) {
            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
            return;
        }
        p.c.removeItemBag(index, 1);
        p.c.eventPoint += 2;
        p.updateExp(20000000);
        if (Util.nextInt(10) < 2) {
            p.updateExp(Util.nextInt(15000000, 20000000));
        } else if (Util.percent(30, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.RUONG_NGOC, false));
        } else if (Util.percent(1000, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_BANG_HOA, false));
        } else if (Util.percent(1001, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BANH_TRUNG_THU_PHONG_LOI, false));
        }
        else if (Util.percent(1002, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.BACH_HO, false));
        }
        else if (Util.percent(1003, 1)) {
            p.c.addItemBag(true, ItemTemplate.itemDefault(ItemName.LAN_SU_VU, false));
        }
        int itemID = RandomItem.TRANG_PHAO.next();
        Item itm = ItemTemplate.itemDefault(itemID);
        if (itemID == ItemName.GAY_MAT_TRANG || itemID == ItemName.GAY_TRAI_TIM) {

                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(100, 150)));
                itm.options.add(new Option(121, Util.nextInt(10, 30)));
                itm.options.add(new Option(84, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(250, 500)));
                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.NHAT_TU_LAM_PHONG || itemID == ItemName.THIEN_NGUYET_CHI_NU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(82, Util.nextInt(3000, 5000)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(80, Util.nextInt(100, 200)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(58, Util.nextInt(5, 10)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }

        if (itemID == ItemName.MAT_NA_THO || itemID == ItemName.MAT_NA_THO_1 || itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(92, Util.nextInt(50, 100)));
                itm.options.add(new Option(94, Util.nextInt(50, 100)));
                itm.options.add(new Option(82, Util.nextInt(5000, 82)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        
        if (itemID == ItemName.MAT_NA_SHIN_AH || itemID == ItemName.MAT_NA_VO_DIEN || itemID == ItemName.MAT_NA_ONI || itemID == ItemName.MAT_NA_KUMA || itemID == ItemName.MAT_NA_INU) {
                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                itm.isExpires = true;
                itm.expires = Util.TimeDay(HSD);
                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                itm.options.add(new Option(82, Util.nextInt(1000, 2000)));
                itm.options.add(new Option(127, Util.nextInt(5, 10)));
                itm.options.add(new Option(128, Util.nextInt(5, 10)));
                itm.options.add(new Option(129, Util.nextInt(5, 10)));
        }
        if (itemID == ItemName.RUONG_BACH_NGAN || itemID == ItemName.RUONG_HUYEN_BI || itemID == ItemName.BAT_BAO) {
            Manager.chatKTG(p.c.name + " sử dụng " + data.name + " đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name);
        }
        p.c.addItemBag(true, itm);
    }
}
