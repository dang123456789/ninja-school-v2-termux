/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Menu;

import assembly.Item;
import assembly.Language;
import assembly.Map;
import assembly.Player;
import assembly.TileMap;
import server.Manager;
import server.Rank;
import server.Service;
import stream.Server;
import stream.TuTienData;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class Vip {

    public static void MenuVip(Player p, byte npcid, byte menuId, byte b3) {
        short[] nam = {712, 713, 746, 747, 748, 749, 750, 751, 752};
        short[] nu = {715, 716, 753, 754, 755, 756, 757, 758, 759};
        short[] ngokhong = {833, 834};
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.nhanquavip == 0) {
                    Service.chatNPC(p, (short) npcid, "Bạn Đã Nhận Qùa Vip Hoặc không đủ điều kiện nhận VIP mốc này");
                    return;
                }
                switch (b3) {
                    case 0: {
                        if (p.vip != 1) {
                            Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
                        if (p.c.getBagNull() < 10) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.gender == 1) {
                            for (byte i = 0; i < 9; i++) {
                                Item itemup = ItemTemplate.itemDefault(nam[i]);
                                p.c.addItemBag(false, itemup);
                            }
                        } else {
                            for (byte i = 0; i < 9; i++) {
                                Item itemup = ItemTemplate.itemDefault(nu[i]);
                                p.c.addItemBag(false, itemup);
                            }
                        }
                        p.conn.sendMessageLog("Bạn đã nhận Quà Vip 1 thành công");
                        p.c.nhanquavip = 0;
                        break;
                    }
                    case 1: {
                        if (p.vip != 2) {
                            Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            break;
                        }
                        if (p.c.getBagNull() < 10) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.gender == 1) {
                            for (byte i = 0; i < 9; i++) {
                                Item itemup = ItemTemplate.itemDefault(nam[i]);
                                itemup.upgradeNext((byte) 8);
                                p.c.addItemBag(false, itemup);
                            }
                        } else {
                            for (byte i = 0; i < 9; i++) {
                                Item itemup = ItemTemplate.itemDefault(nu[i]);
                                itemup.upgradeNext((byte) 8);
                                p.c.addItemBag(false, itemup);
                            }
                        }
                        p.conn.sendMessageLog("Bạn đã nhận vip 2 thành công");
                        p.c.nhanquavip = 0;
                        break;
                    }
                    case 2: {
                        if (p.vip != 3) {
                            Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            break;
                        }
                        if (p.c.getBagNull() < 10) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.gender == 1) {
                            for (byte i = 0; i < 9; i++) {
                                Item itemup = ItemTemplate.itemDefault(nam[i]);
                                itemup.upgradeNext((byte) 16);
                                switch (p.c.get().nclass) {
                                    case 1:
                                    case 2:
                                        itemup.sys = 1;
                                        break;
                                    case 3:
                                    case 4:
                                        itemup.sys = 2;
                                        break;
                                    case 5:
                                    case 6:
                                        itemup.sys = 3;
                                        break;
                                    default:
                                        break;
                                }
                                p.c.addItemBag(false, itemup);
                            }
                        } else {
                            for (byte i = 0; i < 9; i++) {
                                Item itemup = ItemTemplate.itemDefault(nu[i]);
                                itemup.upgradeNext((byte) 16);
                                p.c.addItemBag(false, itemup);
                            }
                        }
                        p.conn.sendMessageLog("Bạn đã nhận vip 3 thành công");
                        p.c.nhanquavip = 0;
                        break;
                    }
                    case 3: {
                        if (p.vip != 4) {
                            Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
                        if (p.c.getBagNull() < 4) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.gender == 1) {
                            Item itemup = ItemTemplate.itemDefault(834);
                            itemup.upgrade = 16;
                            p.c.addItemBag(false, itemup);
                        }
                        if (p.c.gender == 0) {
                            Item itemup = ItemTemplate.itemDefault(833);
                            itemup.upgrade = 16;
                            p.c.addItemBag(false, itemup);
                        }
                        p.conn.sendMessageLog("Trùm đã nhận vip 4 thành công");
                        p.c.nhanquavip = 0;
                        break;
                    }
                    case 4: {
                        if (p.vip != 5) {
                            Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            break;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        Item itemup = ItemTemplate.itemDefault(828);
                        itemup.isLock = true;
                        p.c.addItemBag(false, itemup);
                        p.conn.sendMessageLog("Trùm đã nhận vip 5 thành công");
                        p.c.nhanquavip = 0;
                        break;
                    }
                }
                break;
            }
            case 1: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.vip == -1) {
                    Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                    return;
                }
                switch (p.vip) {
                    case 1: {
                        if (p.c.isDiemDanh == 0) {
                            if (p.c.getBagNull() < 1) {
                                Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                                return;
                            }
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            Item itemup = ItemTemplate.itemDefault(789);
                            itemup.quantity = 10;
                            p.c.addItemBag(false, itemup);
                            p.upluongMessage(5000L);
                            p.c.upxuMessage(500000);
                            p.c.isDiemDanh = 1;
                            Service.chatNPC(p, (short) npcid, "Báo danh VIP 1 thành công, con nhận được 500 lượng.");
                            break;
                        } else {
                            Service.chatNPC(p, (short) npcid, "Hôm nay trùm đã Báo danh VIP, hãy quay lại vào ngày hôm sau nha!");
                        }
                        break;
                    }
                    case 2: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.isDiemDanh == 0) {
                            if (p.c.getBagNull() < 2) {
                                Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                                return;
                            }
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            Item itemup = ItemTemplate.itemDefault(789);
                            itemup.quantity = 20;
                            p.c.addItemBag(false, itemup);
                            p.c.upxuMessage(1000000);
                            p.upluongMessage(10000L);
                            p.c.isDiemDanh = 1;
                            Service.chatNPC(p, (short) npcid, "Trùm báo danh VIP 2 thành công, trùm nhận được 1000 lượng.");
                            break;
                        } else {
                            Service.chatNPC(p, (short) npcid, "Hôm nay trùm đã Báo danh VIP, hãy quay lại vào ngày hôm sau nha!");
                        }
                        break;
                    }
                    case 3: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.isDiemDanh == 0) {
                            if (p.c.getBagNull() < 2) {
                                Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                                return;
                            }
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            Item itemup = ItemTemplate.itemDefault(789);
                            itemup.quantity = 40;
                            p.c.addItemBag(false, itemup);
                            p.upluongMessage(20000L);
                            p.c.upxuMessage(3000000);
                            p.c.isDiemDanh = 1;
                            Service.chatNPC(p, (short) npcid, "Trùm báo danh VIP 3 thành công, trùm nhận được 5000 lượng.");
                            break;
                        } else {
                            Service.chatNPC(p, (short) npcid, "Hôm nay trùm đã Báo danh VIP, hãy quay lại vào ngày hôm sau nha!");
                        }
                        break;
                    }
                    case 4: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.isDiemDanh == 0) {
                            if (p.c.getBagNull() < 6) {
                                Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                                return;
                            }
//                            p.c.addItemBag(false, ItemTemplate.itemDefault(950));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            Item itemup = ItemTemplate.itemDefault(789);
                            itemup.quantity = 50;
                            p.c.addItemBag(false, itemup);
                            p.upluongMessage(30000L);
                            p.c.upxuMessage(5000000);
                            p.c.isDiemDanh = 1;
                            Service.chatNPC(p, (short) npcid, "Trùm báo danh VIP 4 thành công, trùm nhận được 5000 lượng.");
                            break;
                        } else {
                            Service.chatNPC(p, (short) npcid, "Hôm nay trùm đã Báo danh VIP, hãy quay lại vào ngày hôm sau nha!");
                        }
                        break;
                    }
                    case 5: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.isDiemDanh == 0) {
                            if (p.c.getBagNull() < 6) {
                                Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                                return;
                            }
//                            p.c.addItemBag(true, ItemTemplate.itemDefault(998));
//                            p.c.addItemBag(false, ItemTemplate.itemDefault(950));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            p.c.addItemBag(false, ItemTemplate.itemDefault(540));
                            Item itemup = ItemTemplate.itemDefault(789);
                            itemup.quantity = 100;
                            p.c.addItemBag(false, itemup);
                            p.upluongMessage(40000L);
                            p.c.upxuMessage(10000000);
                            p.c.isDiemDanh = 1;
                            Service.chatNPC(p, (short) npcid, "Trùm báo danh VIP 5 thành công, trùm nhận được 5000 lượng.");
                            break;
                        } else {
                            Service.chatNPC(p, (short) npcid, "Hôm nay trùm đã Báo danh VIP, hãy quay lại vào ngày hôm sau nha!");
                        }
                        break;
                    }
                }
                break;
            }
            case 2:
                String nhanqua = "";
                String diemdanh = "";
                if (p.c.nhanquavip == 0) {
                    nhanqua = "Đã Nhận";
                } else {
                    nhanqua = "Chưa Nhận";
                }
                if (p.c.isDiemDanh == 1) {
                    diemdanh = "Đã Báo Danh";
                } else {
                    diemdanh = "Chưa Báo Danh";
                }
                if (p.vip == -1) {
                    p.conn.sendMessageLog("Bạn Không Có Vip");
                } else {
                    p.conn.sendMessageLog("Bạn Hiện Đang Ở Vip " + p.vip + "\n"
                            + "Trạng Thái \n"
                            + "Nhận Quà Vip : " + nhanqua + "\n"
                            + "Điểm Danh Vip : " + diemdanh + "\n"
                    );
                }
                break;
            case 3:
//                if (p.vip < 2) {
//                    Service.chatNPC(p, (short) npcid, "Yêu cầu vip 4 trở lên mới có thể vào map");
//                    return;
//                }
                if (p.c.getEffId(34) == null) {
                    p.conn.sendMessageLog("Phải sử dụng thí luyện thiếp mới có thể vào.");
                    return;
                }
                Map ma = Manager.getMapid(112);
                for (TileMap area : ma.area) {
                    if (area.numplayers < ma.template.maxplayers) {
                        p.c.tileMap.leave(p);
                        area.EnterMap0(p.c);
                        return;
                    }
                }
                break;
            case 4:
                Server.manager.sendTB(p, "BXH VIP", Rank.getStringBXH(5));
                break;
            case 5: {
                Server.manager.sendTB(p, "Mốc Vip", "- VIP ALL TÍNH RIÊNG.\n"
                        + "- Vip 1: 20k\n"
                        + "Nhận 1 set Jirai hoặc Yumito + 0\n"
                        + "- Vip 2: 50k\n"
                        + "Nhận 1 set Jirai hoặc Yumito + 8\n"
                        + "- Vip 3: 70k\n"
                        + "Nhận 1 set Jirai hoặc Yumito + 16\n"
                        + "- Vip 4: 100k\n"
                        + "Nhận 1 set Tôn Ngộ Không + 16 mặt nạ có thể nâng cấp\n"
                        + "- Vip 5: 150k\n"
                        + "Nhận Phượng Hoàng Băng siêu VIP\n");
                break;
            }
        }
    }
}
