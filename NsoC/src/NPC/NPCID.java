/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package NPC;

import History.LichSu;
import Item.ItemName;
import static Item.ItemName.NHAN_THUAT_GIA_TOC_CAP_5;
import Item.RandomItem;
import Upgrade.BiKip;
import Upgrade.Mat;
import Upgrade.UpgradeHoaLong;
import Upgrade.UpgradeKuma;
import Upgrade.UpgradeNhanThuatGiaToc;
import static Upgrade.UpgradeNhanThuatGiaToc.Luong;
import Upgrade.UpgradePet;
import static Upgrade.UpgradePet.xu;
import Upgrade.UpgradePet1;
import static Upgrade.UpgradePet1.Luong;
import assembly.Admission;
import assembly.Char;
import assembly.ClanManager;
import assembly.DunListWin;
import assembly.Item;
import assembly.Language;
import assembly.Level;
import assembly.Map;
import assembly.Option;
import assembly.Player;
import assembly.TileMap;
import static assembly.UseItem.HanSuDung;
import io.Message;
import io.SQLManager;
import io.Util;
import java.io.IOException;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import server.GameSrc;
import server.Manager;
import server.Rank;
import server.Service;
import server.ShinwaManager;
import stream.Cave;
import stream.ChienTruong;
import stream.Client;
import stream.GiaTocChien;
import stream.LanhDiaGiaToc;
import stream.Server;
import template.DanhVongTemplate;
import template.ItemTemplate;
import template.MapTemplate;
import template.MobTemplate;
import template.ShinwaTemplate;
import thiendiabang.ThienDiaBangManager;
import thiendiabang.ThienDiaData;

/**
 *
 * @author Administrator
 */
public class NPCID {
    public static byte count = 0 ;
    public static void npcKanata(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                p.requestItem(2);
                break;
            }
            case 1: {
                switch (b3) {
                    case 0: {
                        if (!p.c.clan.clanName.isEmpty()) {
                            Service.chatNPC(p, (short) npcid, "Hiện tại con đã có gia tộc, không thể thành lập gia tộc được nữa.");
                            return;
                        }
                        if (p.luong < 100000) {
                            Service.chatNPC(p, (short) npcid, "Để thành lập gia tộc, con phải có ít nhất 100.000 lượng trong người.");
                            return;
                        }
                        Service.sendWrite(p, (short) 50, "Tên gia tộc");
                        return;
                    }
                    case 1: {
                        if (p.c.clan.clanName.isEmpty()) {
                            Service.chatNPC(p, (short) npcid, "Hiện tại con chưa có gia tộc, không thể mở Lãnh địa gia tộc.");
                            return;
                        }
                        LanhDiaGiaToc lanhDiaGiaToc = null;
                        if (p.c.ldgtID != -1) {
                            if (LanhDiaGiaToc.ldgts.containsKey(p.c.ldgtID)) {
                                lanhDiaGiaToc = LanhDiaGiaToc.ldgts.get(p.c.ldgtID);
                                if (lanhDiaGiaToc != null && lanhDiaGiaToc.map[0] != null && lanhDiaGiaToc.map[0].area[0] != null) {
                                    if (lanhDiaGiaToc.ninjas.size() <= 50) {
                                        p.c.mapKanata = p.c.mapid;
                                        p.c.tileMap.leave(p);
                                        lanhDiaGiaToc.map[0].area[0].EnterMap0(p.c);
                                        return;
                                    } else {
                                        p.sendAddchatYellow("Số thành viên tham gia Lãnh Địa Gia Tộc đã đạt tối đa.");
                                    }
                                }
                            }
                        }
                        if (lanhDiaGiaToc == null) {
                            if (p.c.clan.typeclan < 3) {
                                Service.chatNPC(p, (short) npcid, "Con không phải tộc trưởng hoặc tộc phó, không thể mở Lãnh địa gia tộc.");
                                return;
                            }
                            if (p.c.getBagNull() < 1) {
                                Service.chatNPC(p, (short) npcid, "Hành trang của con không đủ chỗ trống để nhận Chìa khoá LDGT");
                                return;
                            }
                            ClanManager clan = ClanManager.getClanName(p.c.clan.clanName);
                            if (clan != null && p.c.clan.typeclan >= 3) {
                                if (clan.openDun <= 0) {
                                    Service.chatNPC(p, (short) npcid, "Số lần vào LDGT tuần này đã hết.");
                                    return;
                                }
                                if (clan.ldgtID != -1) {
                                    Service.chatNPC(p, (short) npcid, "Lãnh địa gia tộc của con đang được mở rồi.");
                                    return;
                                }
                                clan.openDun--;
                                clan.flush();
                                lanhDiaGiaToc = new LanhDiaGiaToc();
                                Item itemup = ItemTemplate.itemDefault(260);
                                itemup.quantity = 1;
                                itemup.expires = System.currentTimeMillis() + 600000L;
                                itemup.isExpires = true;
                                itemup.isLock = true;
                                if (p.c.quantityItemyTotal(260) > 0) {
                                    p.c.removeItemBags(260, p.c.quantityItemyTotal(260));
                                }
                                p.c.addItemBag(false, itemup);
                                p.c.ldgtID = lanhDiaGiaToc.ldgtID;
                                clan.ldgtID = lanhDiaGiaToc.ldgtID;
                                lanhDiaGiaToc.clanManager = clan;
                                p.c.mapKanata = p.c.mapid;
                                p.c.tileMap.leave(p);
                                lanhDiaGiaToc.map[0].area[0].EnterMap0(p.c);
                                break;
                            }
                        }
                        break;
                    }
                    case 2: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog("Chức năng này không dành cho phân thân");
                            return;
                        }
                        if (p.c.quantityItemyTotal(262) < 500) {
                            Service.chatNPC(p, (short) npcid, "Con cần có 500 Đồng tiền gia tộc để đổi lấy Túi quà gia tộc.");
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        p.c.removeItemBags(262, 500);
                        Item itemup = ItemTemplate.itemDefault(263);
                        itemup.quantity = 1;
                        itemup.isLock = true;
                        p.c.addItemBag(true, itemup);
                        break;
                    }
                    case 3: {
                        if (p.c.clan.clanName.isEmpty()) {
                            Service.chatNPC(p, (short) npcid, "Con cần phải có gia tộc thì mới có thể điểm danh được nhé");
                            break;
                        }
                        if (p.c.ddClan) {
                            Service.chatNPC(p, (short) npcid, "Hôm nay con đã điểm danh rồi nhé, hãy quay lại đây vào ngày mai");
                            break;
                        }
                        p.c.ddClan = true;
                        ClanManager clan = ClanManager.getClanName(p.c.clan.clanName);
                        if (clan == null) {
                            Service.chatNPC(p, (short) npcid, "Lỗi");
                            return;
                        }
                        p.upExpClan(Util.nextInt(1, 10 + clan.level));
                        p.upluongMessage(25 * clan.level);
                        Service.chatNPC(p, (short) npcid, "Điểm danh thành công. Chúc con chơi game vui vẽ");
                        break;
                    }
                    default: {
                        Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật");
                        break;
                    }
                }
                break;
            }
            case 2: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog("Chức năng này không dành cho phân thân");
                    return;
                }
                if (b3 == 0) {
                    Service.evaluateCave(p.c);
                    return;
                }
                Cave cave = null;
                if (p.c.caveID != -1) {
                    if (Cave.caves.containsKey(p.c.caveID)) {
                        cave = Cave.caves.get(p.c.caveID);
                        if (cave != null && cave.map[0] != null && cave.map[0].area[0] != null) {
                            p.c.mapKanata = p.c.mapid;
                            p.c.tileMap.leave(p);
                            cave.map[0].area[0].EnterMap0(p.c);
                        }
                    }
                } else if (p.c.party != null && p.c.party.cave == null && p.c.party.charID != p.c.id) {
                    p.conn.sendMessageLog("Chỉ có nhóm trưởng mới được phép mở cửa hang động");
                    return;
                }

                if (cave == null) {
                    if (p.c.nCave <= 0) {
                        Service.chatNPC(p, (short) npcid, "Số lần vào hang động của con hôm nay đã hết, hãy quay lại vào ngày mai.");
                        return;
                    }
                    if (b3 == 1) {
                        if (p.c.level < 30 || p.c.level > 39) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 30 || p.c.party.aChar.get(i).level > 39) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(3);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(3);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 1;
                    }
                    if (b3 == 2) {
                        if (p.c.level < 40 || p.c.level > 49) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 40 || p.c.party.aChar.get(i).level > 49) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(4);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(4);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 0;
                    }
                    if (b3 == 3) {
                        if (p.c.level < 50 || p.c.level > 59) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 50 || p.c.party.aChar.get(i).level > 59) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(5);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(5);
                        }
                        p.c.caveID = cave.caveID;
                    }
                    if (b3 == 4) {
                        if (p.c.level < 60 || p.c.level > 69) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null && p.c.party.aChar.size() > 1) {
                            p.conn.sendMessageLog("Hang động này chỉ được phép 1 mình.");
                            return;
                        }
                        cave = new Cave(6);
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 1;
                    }
                    if (b3 == 5) {
                        if (p.c.level < 70 || p.c.level > 89) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 70 || p.c.party.aChar.get(i).level > 89) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(7);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(7);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 0;
                    }
                    if (b3 == 6) {
                        if (p.c.level < 90 || p.c.level > 170) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 90 || p.c.party.aChar.get(i).level > 170) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(9);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(9);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 0;
                    }
                    // sửa
                    if (b3 == 7) {
                        if (p.c.level < 130 || p.c.level > 170) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 130 || p.c.party.aChar.get(i).level > 170) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(2);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(2);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 0;
                    }
                    // sửa
                    if (b3 == 7) {
                        if (p.c.level < 130 || p.c.level > 170) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 130 || p.c.party.aChar.get(i).level > 170) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(2);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(2);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 0;
                    }
                    // LIÊN HANG
                    if (b3 == 8) {
                        if (p.c.level < 130 || p.c.level > 170) {
                            p.conn.sendMessageLog("Trình độ không phù hợp");
                            return;
                        }
                        if (p.c.party != null) {
                            synchronized (p.c.party.aChar) {
                                for (byte i = 0; i < p.c.party.aChar.size(); ++i) {
                                    if (p.c.party.aChar.get(i).level < 130 || p.c.party.aChar.get(i).level > 170) {
                                        p.conn.sendMessageLog("Thành viên trong nhóm có trình độ không phù hợp");
                                        return;
                                    }
                                }
                            }
                        }
                        if (p.c.party != null) {
                            if (p.c.party.cave == null) {
                                cave = new Cave(1);
                                p.c.party.openCave(cave, p.c.name);
                            } else {
                                cave = p.c.party.cave;
                            }
                        } else {
                            cave = new Cave(1);
                        }
                        p.c.caveID = cave.caveID;
                        p.c.isHangDong6x = 0;
                    }
                    // Nếu Lỗi Thì Sửa Lại
                    if (cave != null) {
                        p.c.nCave--;
                        p.c.pointCave = 0;
                        if (p.c.party != null && p.c.party.charID == p.c.id) {
                            if (p.c.party.aChar != null && p.c.party.aChar.size() > 0) {
                                synchronized (p.c.party.aChar) {
                                    Char _char;
                                    for (int i = 0; i < p.c.party.aChar.size(); i++) {
                                        if (p.c.party.aChar.get(i) != null) {
                                            _char = p.c.party.aChar.get(i);
                                            if (_char.id != p.c.id && p.c.tileMap.getNinja(_char.id) != null && _char.nCave > 0 && _char.caveID == -1 && _char.level >= 30 && (int) _char.level / 10 == cave.x) {
                                                _char.nCave--;
                                                _char.pointCave = 0;
                                                _char.caveID = p.c.caveID;
                                                _char.isHangDong6x = p.c.isHangDong6x;
                                                _char.mapKanata = _char.mapid;
                                                _char.countHangDong++;
                                                if (_char.pointUydanh < 5000) {
                                                    _char.pointUydanh += 5;
                                                }
                                                _char.tileMap.leave(_char.p);
                                                cave.map[0].area[0].EnterMap0(_char);
                                                _char.p.setPointPB(_char.pointCave);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        p.c.mapKanata = p.c.mapid;
                        p.c.countHangDong++;
                        if (p.c.pointUydanh < 5000) {
                            p.c.pointUydanh += 5;
                        }
                        p.c.tileMap.leave(p);
                        cave.map[0].area[0].EnterMap0(p.c);
                    }
                }
                p.setPointPB(p.c.pointCave);
                break;
            }
            case 3: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.party != null && p.c.party.charID != p.c.id) {
                            Service.chatNPC(p, (short) npcid, "Con không phải trưởng nhóm, không thể thực hiện gửi lời mời lôi đài cho người/nhóm khác");
                            return;
                        }
                        Service.sendInputDialog(p, (short) 2, "Nhập tên đối thủ của con");
                        return;
                    }
                    case 1: {
                        Service.sendLoiDaiList(p.c);
                        return;
                    }
                    case 2: {
                        String alert = "";
                        for (int i = 0; i < DunListWin.dunList.size(); ++i) {
                            int temp = i + 1;
                            alert = alert + temp + ". Phe " + ((DunListWin) DunListWin.dunList.get(i)).win + " thắng Phe " + ((DunListWin) DunListWin.dunList.get(i)).lose + ".\n";
                        }
                        Server.manager.sendTB(p, "Kết quả", alert);
                        break;
                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            case 4: {
                Service.chatNPC(p, (short) npcid, "Vũ khí của ta cực sắc bén. Nếu muốn tỷ thí thì cứ đến chỗ ta!");
                break;
            }
        }
    }
///
    public static void npcVip(Player p, byte npcid, byte menuId, byte b3) {
        if (p.c.isNhanban) {
            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
            return;
        }
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.getBagNull() < 4) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                    return;
                }
//                if (p.c.isVIP == 1 && p.c.quamocvip == 0) {
                    Item antoc = ItemTemplate.itemDefault(839);
//                    antoc.options.add(new Option(87, 5000));
//                    antoc.options.add(new Option(57, 100));
//                    antoc.options.add(new Option(85, 10));
//                    antoc.options.add(new Option(80, 500));
//                    antoc.options.add(new Option(98, 10));
//                    antoc.options.add(new Option(85, 9));
//                    antoc.options.add(new Option(87, 5000));
//                    antoc.options.add(new Option(100, 10));
//                    antoc.options.add(new Option(60, 10));
//                    antoc.options.add(new Option(61, 10));
//                    antoc.options.add(new Option(62, 50));
//                    antoc.options.add(new Option(36, 500));
//                    antoc.options.add(new Option(46, 10));
                    antoc.upgrade = 16;
                    Item thuhon = ItemTemplate.itemDefault(866);
//                    thuhon.options.add(new Option(82, 5000));
//                    thuhon.options.add(new Option(87, 5000));
//                    thuhon.options.add(new Option(100, 40));
//                    thuhon.options.add(new Option(115, 500));
//                    thuhon.options.add(new Option(116, 500));
//                    thuhon.options.add(new Option(114, 500));
//                    thuhon.options.add(new Option(74, 300));
//                    thuhon.options.add(new Option(82, 5000));
//                    thuhon.options.add(new Option(83, 5000));
//                    thuhon.options.add(new Option(0, 5000));
//                    thuhon.options.add(new Option(1, 5000));
//                    thuhon.options.add(new Option(85, 9));
//                    thuhon.options.add(new Option(76, 5000));
////                    thuhon.options.add(new Option(93, 5000));
//                    thuhon.options.add(new Option(91, 5));
//                    thuhon.options.add(new Option(92, 500));
//                    thuhon.options.add(new Option(87, 5000));
//                    thuhon.options.add(new Option(101, 30));
//                    thuhon.options.add(new Option(79, 40));
//                    thuhon.options.add(new Option(81, 200));
//                    thuhon.options.add(new Option(76, 5000));
                    thuhon.upgrade = 16;
//                    Item aodai = ItemTemplate.itemDefault(p.c.gender == 1 ? 796 : 795);
//                    aodai.options.add(new Option(65, 0));
//                    aodai.options.add(new Option(66, 1000));
//                    aodai.options.add(new Option(58, 20));
//                    aodai.options.add(new Option(94, 15));
//                    aodai.options.add(new Option(10, 10));
//                    aodai.options.add(new Option(67, 5));
//                    aodai.options.add(new Option(68, 10));
//                    aodai.options.add(new Option(71, 5));
//                    aodai.upgrade = 16;
                    //
                    Item mat = ItemTemplate.itemDefault(694);
                    mat.options.add(new Option(85, 9));
                    mat.options.add(new Option(58, 25));
                    mat.options.add(new Option(82, 3000));
                    mat.options.add(new Option(87, 5000));
                    mat.options.add(new Option(100, 30));
                    mat.options.add(new Option(113, 5000));
                    mat.upgrade = 16;
                    //
                    p.c.addItemBag(false, antoc);
                    p.c.addItemBag(false, thuhon);
//                    p.c.addItemBag(false, aodai);
                    p.c.addItemBag(false, mat);
                    p.conn.sendMessageLog("Bạn đã nhận quà mốc [VIP FREE] thành công");
                    p.c.quamocvip = 1;
//                } else {
//                    p.conn.sendMessageLog("Bạn Đã Nhận Hoặc Chưa phải [VIP] Để Nhận Thưởng");
//                }
                break;
            }
            case 1: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 4) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.isVIP == 2 && p.c.quamocvip == 1) {
                            Item tbdq = ItemTemplate.itemDefault(865);
                            tbdq.options.add(new Option(82, 20000));
                            tbdq.options.add(new Option(102, 120000));
                            tbdq.options.add(new Option(87, 10000));
                            tbdq.options.add(new Option(100, 100));
                            tbdq.options.add(new Option(115, 400));
                            tbdq.options.add(new Option(116, 300));
                            tbdq.options.add(new Option(114, 300));
                            tbdq.options.add(new Option(74, 300));
                            tbdq.options.add(new Option(0, 2000));
                            tbdq.options.add(new Option(1, 2000));
                            tbdq.options.add(new Option(8, 200));
                            tbdq.options.add(new Option(9, 200));
                            tbdq.options.add(new Option(85, 9));
                            tbdq.options.add(new Option(80, 1000));
//                            tbdq.options.add(new Option(76, 10000));
//                            tbdq.options.add(new Option(101, 10));
//                            tbdq.options.add(new Option(98, 10));
                            tbdq.upgrade = 16;
                            Item ktv = ItemTemplate.itemDefault(864);
                            ktv.options.add(new Option(58, 25));
                            ktv.options.add(new Option(87, 15000));
                            ktv.options.add(new Option(94, 250));
                            ktv.options.add(new Option(8, 200));
                            ktv.options.add(new Option(9, 200));
                            ktv.options.add(new Option(86, 100));
                            ktv.options.add(new Option(100, 20));
                            ktv.options.add(new Option(63, 20));
                            ktv.options.add(new Option(79, 20));
                            ktv.options.add(new Option(98, 10));
                            ktv.options.add(new Option(99, 500));
                            ktv.options.add(new Option(105, 5000));
                            ktv.upgrade = 16;
                            ktv.upgrade = 16;
                            p.c.addItemBag(false, tbdq);
                            p.c.addItemBag(false, ktv);
                            p.conn.sendMessageLog("Bạn đã nhận quà mốc [V-VIP] thành công");
                            p.c.quamocvip = 2;
                        } else {
                            p.conn.sendMessageLog("Bạn Đã Nhận Hoặc Chưa phải [V-VIP] Để Nhận Thưởng");
                        }
                        break;
                    }
                    case 1: {
                        if (p.c.isVIP >= 1) {
                            Map ma = Manager.getMapid(198);
                            for (TileMap area : ma.area) {
                                if (area.numplayers < ma.template.maxplayers) {
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                            }
                        } else {
                            p.conn.sendMessageLog("Bạn không phải [V-VIP]");
                        }
                    }
                }
            }
            case 2: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 5) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.isVIP == 3 && p.c.quamocvip == 2) {
                            Item mnst = ItemTemplate.itemDefault(p.c.gender == 1 ? 405 : 406);
                            mnst.isExpires = false;
                            mnst.expires = -1L;
                            mnst.options.clear();
                            mnst.options.add(new Option(73, 20000));
                            mnst.options.add(new Option(57, 500));
                            mnst.options.add(new Option(58, 50));
                            mnst.options.add(new Option(8, 420));
                            mnst.options.add(new Option(9, 420));
                            mnst.options.add(new Option(10, 1000));
                            mnst.options.add(new Option(68, 1000));
                            mnst.options.add(new Option(60, 20));
                            mnst.options.add(new Option(61, 20));
                            mnst.options.add(new Option(62, 400));
                            mnst.options.add(new Option(36, 600));
                            mnst.options.add(new Option(46, 60));
                            mnst.options.add(new Option(48, 600));
                            mnst.options.add(new Option(49, 600));
                            mnst.options.add(new Option(50, 600));
                            mnst.options.add(new Option(51, 1000));
                            mnst.options.add(new Option(52, 1000));
                            mnst.options.add(new Option(53, 1000));
                            mnst.options.add(new Option(54, 20));
                            mnst.options.add(new Option(55, 20));
                            mnst.options.add(new Option(56, 20));
                            mnst.options.add(new Option(85, 9));
                            mnst.options.add(new Option(76, 10000));
                            mnst.options.add(new Option(100, 100));
                            mnst.upgrade = 16;
                            Item tbdq = ItemTemplate.itemDefault(952);
                            tbdq.options.add(new Option(82, 20000));
                            tbdq.options.add(new Option(102, 120000));
                            tbdq.options.add(new Option(87, 10000));
                            tbdq.options.add(new Option(100, 100));
                            tbdq.options.add(new Option(115, 400));
                            tbdq.options.add(new Option(116, 300));
                            tbdq.options.add(new Option(114, 300));
                            tbdq.options.add(new Option(74, 300));
                            tbdq.options.add(new Option(0, 2000));
                            tbdq.options.add(new Option(1, 2000));
                            tbdq.options.add(new Option(8, 200));
                            tbdq.options.add(new Option(9, 200));
                            tbdq.options.add(new Option(85, 9));
                            tbdq.options.add(new Option(80, 1000));
                            tbdq.options.add(new Option(76, 10000));
                            tbdq.options.add(new Option(101, 10));
                            tbdq.options.add(new Option(98, 10));
                            tbdq.upgrade = 16;
                            Item ktv = ItemTemplate.itemDefault(953);
                            ktv.options.add(new Option(58, 25));
                            ktv.options.add(new Option(87, 15000));
                            ktv.options.add(new Option(94, 250));
                            ktv.options.add(new Option(8, 200));
                            ktv.options.add(new Option(9, 200));
                            ktv.options.add(new Option(86, 100));
                            ktv.options.add(new Option(100, 20));
                            ktv.options.add(new Option(63, 20));
                            ktv.options.add(new Option(79, 20));
                            ktv.options.add(new Option(98, 10));
                            ktv.options.add(new Option(99, 500));
                            ktv.options.add(new Option(105, 5000));
                            ktv.upgrade = 16;
                            Item quanphep = ItemTemplate.itemDefault(999);
                            quanphep.options.clear();
                            quanphep.options.add(new Option(85, 9));
                            quanphep.options.add(new Option(94, 800));
                            quanphep.options.add(new Option(58, 20));
                            quanphep.options.add(new Option(82, 10000));
                            quanphep.options.add(new Option(8, 1000));
                            quanphep.options.add(new Option(9, 1000));
                            quanphep.options.add(new Option(69, 3000));
                            quanphep.options.add(new Option(67, 2000));
                            quanphep.options.add(new Option(79, 10));
                            quanphep.options.add(new Option(80, 2000));
                            quanphep.options.add(new Option(103, 30000));
                            quanphep.options.add(new Option(105, 5000));
                            quanphep.options.add(new Option(48, 7000));
                            quanphep.options.add(new Option(49, 9000));
                            quanphep.options.add(new Option(50, 2000));
                            quanphep.upgrade = 16;
                            p.c.addItemBag(false, mnst);
                            p.c.addItemBag(false, ktv);
                            p.c.addItemBag(false, tbdq);
                            p.c.addItemBag(false, quanphep);
                            p.conn.sendMessageLog("Bạn đã nhận quà mốc [S-VIP] thành công");
                            p.c.quamocvip = 3;
                        } else {
                            p.conn.sendMessageLog("Bạn Đã Nhận Hoặc Chưa phải [S-VIP] Để Nhận Thưởng");
                        }
                        break;
                    }
                    case 1: {
                        if (p.c.isVIP >= 2) {
                            Map ma = Manager.getMapid(198);
                            for (TileMap area : ma.area) {
                                if (area.numplayers < ma.template.maxplayers) {
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                            }
                        } else {
                            p.conn.sendMessageLog("Bạn không phải [S-VIP]");
                        }
                    }
                }
            }
        }
    }
    public static void npcFuroya(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                switch (b3) {
                    case 0:
                        p.requestItem(21 - p.c.gender);
                        return;
                    case 1:
                        p.requestItem(23 - p.c.gender);
                        return;
                    case 2:
                        p.requestItem(25 - p.c.gender);
                        return;
                    case 3:
                        p.requestItem(27 - p.c.gender);
                        return;
                    case 4:
                        p.requestItem(29 - p.c.gender);
                        return;
                    default:
                        Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                        return;
                }
            case 1:
                Service.chatNPC(p, (short) npcid, "Tan bán quần áo, mũ nón, găng tay và giày siêu bền, siêu rẻ!");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcAmeji(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                switch (b3) {
                    case 0: {
                        p.requestItem(16);
                        break;
                    }
                    case 1: {
                        p.requestItem(17);
                        break;
                    }
                    case 2: {
                        p.requestItem(18);
                        break;
                    }
                    case 3: {
                        p.requestItem(19);
                        break;
                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            case 1: {
                ItemTemplate data;
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.level < 50) {
                            Service.chatNPC(p, (short) npcid, "Cấp độ của con không đủ để nhận nhiệm vụ này");
                            return;
                        }

                        if (p.c.countTaskDanhVong < 1) {
                            Service.chatNPC(p, (short) npcid, "Số lần nhận nhiệm vụ của con hôm nay đã hết");
                            return;
                        }

                        if (p.c.isTaskDanhVong == 1) {
                            Service.chatNPC(p, (short) npcid, "Trước đó con đã nhận nhiệm vụ rồi, hãy hoàn thành đã nha");
                            return;
                        }

                        int type = DanhVongTemplate.randomNVDV();
                        p.c.taskDanhVong[0] = type;
                        p.c.taskDanhVong[1] = 0;
                        p.c.taskDanhVong[2] = DanhVongTemplate.targetTask(type);
                        p.c.isTaskDanhVong = 1;
                        p.c.countTaskDanhVong--;
                        if (p.c.isTaskDanhVong == 1) {
                            String nv = "Nhiệm Vụ Lần Này : \n"
                                    + String.format(DanhVongTemplate.nameNV[p.c.taskDanhVong[0]],
                                            p.c.taskDanhVong[1],
                                            p.c.taskDanhVong[2])
                                    + "\n\n- Số lần nhận nhiệm vụ còn lại là: " + p.c.countTaskDanhVong;
                            Server.manager.sendTB(p, "Nhiệm vụ", nv);
                        }
                        break;
                    }
                    case 1: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.isTaskDanhVong == 0) {
                            Service.chatNPC(p, (short) npcid, "Con chưa nhận nhiệm vụ nào cả!");
                            return;
                        }

                        if (p.c.taskDanhVong[1] < p.c.taskDanhVong[2]) {
                            Service.chatNPC(p, (short) npcid, "Con chưa hoàn thành nhiệm vụ ta giao!");
                            return;
                        }

                        if (p.c.getBagNull() < 2) {
                            Service.chatNPC(p, (short) npcid, "Hành trang của con không đủ chỗ trống để nhận thưởng");
                            return;
                        }

                        int point = 5; // điểm mỗi nhiệm vụ
                        if (p.c.taskDanhVong[0] == 9) {
                            point = 5;
                        }

                        p.c.isTaskDanhVong = 0;
                        p.c.taskDanhVong = new int[]{-1, -1, -1, 0, p.c.countTaskDanhVong};
                        Item item = ItemTemplate.itemDefault(DanhVongTemplate.randomDaDanhVong(), false);
                        item.quantity = 1;
                        item.isLock = false;
                        if (p.c.pointUydanh < 5000) {
                            ++p.c.pointUydanh;
                        }
                        p.c.addItemBag(true, item);
                        int type = Util.nextInt(10);
                        if (p.c.avgPointDanhVong(p.c.getPointDanhVong(type))) {
                            for (int i = 0; i < 10; i++) {
                                type = i;
                                if (!p.c.avgPointDanhVong(p.c.getPointDanhVong(type))) {
                                    break;
                                }
                            }
                        }
                        p.c.plusPointDanhVong(type, point);
                        Service.chatNPC(p, (short) npcid, "Con hãy nhận lấy phần thưởng của mình.");
                        break;
                    }
                    case 2: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.isTaskDanhVong == 0) {
                            Service.chatNPC(p, (short) 2, "Con chưa nhận nhiệm vụ nào cả!");
                            return;
                        }
                        p.c.isTaskDanhVong = 0;
                        p.c.taskDanhVong = new int[]{-1, -1, -1, 0, p.c.countTaskDanhVong};
                        Service.chatNPC(p, (short) 2, "Con đã huỷ nhiệm vụ lần này.");
                        break;
                    }
                    case 3: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.checkPointDanhVong(1)) {
                            if (p.c.getBagNull() < 1) {
                                Service.chatNPC(p, (short) npcid, "Hành trang của con không đủ chỗ trống để nhận thưởng");
                                return;
                            }
                            Item item = ItemTemplate.itemDefault(685, true);
                            item.quantity = 1;
                            item.upgrade = 1;
                            item.isLock = true;
                            item.options.add(new Option(6, 1000));
                            item.options.add(new Option(87, 1000));
                            item.options.add(new Option(80, 50));
                            item.options.add(new Option(94, 10));
                            item.options.add(new Option(100, 10));
                            p.c.addItemBag(false, item);
                        } else {
                            Service.chatNPC(p, (short) npcid, "Con chưa đủ điểm để nhận mắt");
                        }
                        break;
                    }
                    case 4: {
                        Item Item = p.c.ItemBody[14];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (Item == null) {
                            Service.chatNPC(p, (short) npcid, "Hãy đeo mắt vào người trước rồi nâng cấp nhé.");
                            return;
                        }
                        if (Item.upgrade >= 10) {
                            Service.chatNPC(p, (short) npcid, "Mắt của con đã đạt cấp tối đa");
                            return;
                        }
                        if (!p.c.checkPointDanhVong(Item.upgrade)) {
                            Service.chatNPC(p, (short) npcid, "Con chưa đủ điểm danh vọng để thực hiện nâng cấp");
                            return;
                        }
                        data = ItemTemplate.ItemTemplateId(Item.id);
                        Service.startYesNoDlg(p, (byte) 0,
                                "Bạn có muốn nâng cấp " + data.name + " với "
                                + Mat.coinUpMat[Item.upgrade]
                                + " yên hoặc xu với tỷ lệ thành công là "
                                + Mat.percentUpMat[Item.upgrade]
                                + "% không?");
                        break;
                    }
                    case 5: {
                        Item Item = p.c.ItemBody[14];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (Item == null) {
                            Service.chatNPC(p, (short) npcid, "Hãy đeo mắt vào người trước rồi nâng cấp nhé.");
                            return;
                        }
                        if (Item.upgrade >= 10) {
                            Service.chatNPC(p, (short) npcid, "Mắt của con đã đạt cấp tối đa");
                            return;
                        }
                        if (!p.c.checkPointDanhVong(Item.upgrade)) {
                            Service.chatNPC(p, (short) npcid, "Con cần đủ 100 điểm mỗi loại để nâng cấp");
                            return;
                        }
                        data = ItemTemplate.ItemTemplateId(Item.id);
                        Service.startYesNoDlg(p, (byte) 1,
                                "Bạn có muốn nâng cấp " + data.name + " với "
                                + Mat.coinUpMat[Item.upgrade]
                                + " yên hoặc xu và " + Mat.goldUpMat[Item.upgrade]
                                + " lượng với tỷ lệ thành công là "
                                + Mat.percentUpMat[Item.upgrade] * 2 + "% không?");
                        break;
                    }
                    case 6: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        String nv = "- Hoàn thành nhiệm vụ. Hãy gặp Ameji để trả nhiệm vụ.\n"
                                + "- Hôm nay có thể nhận thêm " + p.c.countTaskDanhVong + " nhiệm vụ trong ngày.\n"
                                + "- Hôm nay có thể sử dụng thêm " + p.c.useDanhVongPhu + " Danh Vọng Phù để nhận thêm 30 lần làm nhiệm vụ.\n"
                                + "- Hoàn thành nhiệm vụ sẽ nhận ngẫu nhiên 1 viên đá danh vọng cấp 1-5.\n"
                                + "- Khi đủ mốc 100 điểm mỗi loại có thể nhận mắt và nâng cấp mắt.";
                        if (p.c.isTaskDanhVong == 1) {
                            nv = "Nhiệm Vụ Lần Này: \n" + String.format(DanhVongTemplate.nameNV[p.c.taskDanhVong[0]], p.c.taskDanhVong[1], p.c.taskDanhVong[2]) + "\n\n" + nv;
                        }
                        Server.manager.sendTB(p, "Nhiệm vụ", nv);
                        break;
                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            case 2: {
                Service.chatNPC(p, (short) npcid, "Tan bán các loại trang sức lấp lánh!");
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

    public static void npcKiriko(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                p.requestItem(7);
                break;
            }
            case 1: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }

                p.requestItem(6);
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcTabemono(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                p.requestItem(9);
                break;
            case 1:
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }

                p.requestItem(8);
                break;
            case 2: {
                Service.chatNPC(p, (short) npcid, "Ăn vào không chết mịa bây giờ , Ngu thì chết khóc lóc con cặc!");
                break;
            }
             case 3: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (b3) {
                    case 0: {
                        if (!ThienDiaBangManager.register) {
                            Service.chatNPC(p, (short) npcid, "Đang trong thời gian tổng kết. Hiện tại không thể đăng ký.");
                            return;
                        }
                        if (ThienDiaBangManager.diaBangList.containsKey(p.c.name) || ThienDiaBangManager.thienBangList.containsKey(p.c.name)) {
                            Service.chatNPC(p, (short) npcid, "Con đã đăng ký trước đó rồi");
                            return;
                        }
                        if (p.c.get().level >= 50 && p.c.get().level < 70) {
                            ThienDiaBangManager.diaBangList.put(p.c.name, new ThienDiaData(p.c.name, ThienDiaBangManager.rankDiaBang++, 1));
                            Service.chatNPC(p, (short) npcid, "Con đã đăng ký tham gia trang tài Địa bảng thành công.");
                        } else if (p.c.get().level >= 70) {
                            ThienDiaBangManager.thienBangList.put(p.c.name, new ThienDiaData(p.c.name, ThienDiaBangManager.rankThienBang++, 1));
                            Service.chatNPC(p, (short) npcid, "Con đã đăng ký tham gia tranh tài Thiên bảng thành công.");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không phù hợp để đăng ký tham gia tranh tài.");
                        }
                        break;
                    }
                    case 1: {
                        if (!ThienDiaBangManager.register) {
                            Service.chatNPC(p, (short) npcid, "Đang trong thời gian tổng kết. Hiện tại không thể thi đấu.");
                            return;
                        }
                        ArrayList<ThienDiaData> list = new ArrayList<>();
                        if (ThienDiaBangManager.diaBangList.containsKey(p.c.name)) {
                            ThienDiaData rank = ThienDiaBangManager.diaBangList.get(p.c.name);
                            for (ThienDiaData data : ThienDiaBangManager.getListDiaBang()) {
                                if (data != null) {
                                    if (rank.getRank() < 10 && (data.getRank() - rank.getRank()) < 20) {
                                        list.add(data);
                                    } else if (data.getRank() < rank.getRank() & (rank.getRank() - data.getRank()) < 10) {
                                        list.add(data);
                                    }
                                }
                            }
                        } else if (ThienDiaBangManager.thienBangList.containsKey(p.c.name)) {
                            ThienDiaData rank = ThienDiaBangManager.thienBangList.get(p.c.name);
                            for (ThienDiaData data : ThienDiaBangManager.getListThienBang()) {
                                if (data != null) {
                                    if (rank.getRank() < 10 && (data.getRank() - rank.getRank()) < 20) {
                                        list.add(data);
                                    } else if (data.getRank() <= rank.getRank() & (rank.getRank() - data.getRank()) < 10) {
                                        list.add(data);
                                    }
                                }
                            }
                        } else {
                            Service.chatNPC(p, (short) npcid, "Con chưa đăng ký tham gia thi đấu.");
                            return;
                        }
                        Service.SendChinhPhuc(p, list);
                        return;
                    }
                    case 2: {
                        String res = "";
                        int count = 1;
                        for (ThienDiaData data : ThienDiaBangManager.getListSortAsc(new ArrayList<ThienDiaData>(ThienDiaBangManager.thienBangList.values()))) {
                            if (count < 11) {
                                res += "Hạng " + count + ": " + data.getName() + ".\n";
                                count++;
                            }
                        }
                        Server.manager.sendTB(p, "Thiên bảng", res);
                        return;
                    }
                    case 3: {
                        String res = "";
                        int count = 1;
                        for (ThienDiaData data : ThienDiaBangManager.getListSortAsc(new ArrayList<ThienDiaData>(ThienDiaBangManager.diaBangList.values()))) {
                            if (count < 11) {
                                res += "Hạng " + count + ": " + data.getName() + ".\n";
                                count++;
                            }
                        }
                        Server.manager.sendTB(p, "Địa bảng", res);
                        return;
                    }
                    case 5: {
                        Server.manager.sendTB(p, "Hướng dẫn", "- Thiên Địa Bảng sẽ được mở hàng tuần. Bắt đầu từ thứ 2 và tổng kết vào chủ nhật.\n"
                                + "- Thiên Địa Bảng sẽ được mở đăng ký và chính phục từ 00h05' đến 23h45' hàng ngày. Mỗi ngày sẽ có 20p để tổng kết ngày, trong thời gian này sẽ không thể đăng ký và chinh phục\n"
                                + "- Trong thời gian tổng kết nếu chiến thắng trong Chinh phục sẽ không được tính rank."
                                + "- Vào ngày thường sẽ không giới hạn lượt thách đấu.\n"
                                + "- Vào Thứ 7 và Chủ Nhật mỗi Ninja sẽ có 5 lượt thách đấu, Thắng sẽ không bị mất lượt, thua sẽ bị trừ 1 lần thách đấu."
                                + "- Địa Bảng dành cho ninja từ cấp độ 50-69.\n"
                                + "- Thiên Bảng dành cho ninja từ cấp độ trên 70\n"
                                + "- Sau khi đăng ký thành công, hãy Chinh Phục ngay để giành lấy vị trí top đầu.\n"
                                + "- Mỗi lần chiến thắng, nếu vị trí của đối thủ trước bạn, bạn sẽ đổi vị trí của mình cho đối thủ, còn không vị trí của bạn sẽ được giữ nguyên.\n"
                                + "- Phần thưởng sẽ được trả thưởng vào mỗi tuần mới (Lưu ý: Hãy nhận thưởng ngay trong tuần mới đó, nếu sang tuần sau phần thưởng sẽ bị reset).\n\n"
                                + "- PHẦN THƯỞNG: \n"
                                );
                        break;
                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcKamakura(Player p, byte npcid, byte menuId, byte b3) {
        try {
//            if (p.c.isNhanban) {
//                p.conn.sendMessageLog("Chức năng này không dành cho phân thân.");
//                return;
//            }
            switch (menuId) {
                case 0:
                    switch (b3) {
                        case 0: {
                            Service.openMenuBox(p);
                            break;
                        }
                        case 1: {
                            Service.openMenuBST(p);
                            break;
                        }
                        case 2: {
                            Service.openMenuCaiTrang(p);
                            break;
                        }
                        case 3: {
                            //Tháo cải trang
                            p.c.caiTrang = -1;
                            Message m = new Message(11);
                            m.writer().writeByte(-1);
                            m.writer().writeByte(p.c.get().speed());
                            m.writer().writeInt(p.c.get().getMaxHP());
                            m.writer().writeInt(p.c.get().getMaxMP());
                            m.writer().writeShort(p.c.get().eff5buffHP());
                            m.writer().writeShort(p.c.get().eff5buffMP());
                            m.writer().flush();
                            p.conn.sendMessage(m);
                            m.cleanup();
                            Service.CharViewInfo(p, false);
                            p.endLoad(true);
                            break;
                        }
                    }
                    break;
                case 1:
                    if (p.c.tileMap.map.getXHD() != -1 || p.c.tileMap.map.LangCo() || p.c.tileMap.map.mapChienTruong() || p.c.tileMap.map.mapBossTuanLoc() || p.c.tileMap.map.mapLDGT() || p.c.tileMap.map.mapGTC() || p.c.tileMap.map.id == 111 || p.c.tileMap.map.id == 113) {
                        p.c.mapLTD = 22;
                    } else {
                        p.c.mapLTD = p.c.tileMap.map.id;
                    }
                    Service.chatNPC(p, (short) npcid, "Lưu toạ độ thành công! Khi chết con sẽ được vác xác về đây.");
                    break;
                case 2:
                    switch (b3) {
                        case 0:
                            if (p.c.level < 60) {
                                p.conn.sendMessageLog("Chức năng này yêu cầu trình độ 60");
                                return;
                            }
                            Map ma = Manager.getMapid(139);
                            TileMap area;
                            int var8;
                            for (var8 = 0; var8 < ma.area.length; ++var8) {
                                area = ma.area[var8];
                                if (area.numplayers < ma.template.maxplayers) {
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                            }
                            return;
                        case 1:
                            Service.chatNPC(p, (short) npcid, "Để phiêu lưu Vùng Đất Ma Quỷ các ninja cần đạt trình độ cấp 60. \n Phân thân có thể vào Vùng Đất Ma Quỷ khi sở hữu Thí Luyện Thiếp \n Khi tham gia đánh quái ở Vùng Đất Ma Quỷ kèm theo vật phẩm Thiên Nhãn Phù khi đánh quái có thể rơi ra nhiều vật phẩm đặc biệt");
                            return;
                        default:
                            return;
                    }
                default: {
                    Service.chatNPC(p, (short) npcid, "nooooooooo!");
                    break;
                }
            }
        } catch (IOException e) {
        }
    }

    public static void npcKenshinto(Player p, byte npcid, byte menuId, byte b3) {
        if (p.c.isNhanban) {
            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
            return;
        }
        switch (menuId) {
            case 0: {
                switch (b3) {
                    case 0:
                        p.requestItem(10);
                        break;
                    case 1:
                        p.requestItem(31);
                        break;
                    case 2:
                        Server.manager.sendTB(p, "Hướng dẫn", "");
                        break;
                    default:
                        break;
                }
                break;
            }
            case 1: {
                if (b3 == 0) {
                    p.requestItem(12);
                } else if (b3 == 1) {
                    p.requestItem(11);
                }
                break;
            }
            case 2: {
                p.requestItem(13);
                break;
            }
            case 3: {
                p.requestItem(33);
                break;
            }
            case 4: {
                p.requestItem(46);
                break;
            }
            case 5: {
                p.requestItem(47);
                break;
            }
            case 6: {
                p.requestItem(49);
                break;
            }
            case 7: {
                p.requestItem(50);
                break;
            }
            case 8: {
                Service.chatNPC(p, (short) npcid, "Cần nâng cấp trang bị, hãy đến quán của ta!");
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcUmayaki_Lang(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                Service.chatNPC(p, (short) npcid, "Ta kéo xe qua các làng với tốc độ ánh sáng!");
                return;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
                TileMap[] var5 = Manager.getMapid(Map.arrLang[menuId - 1]).area;
                int var6 = var5.length;

                for (int var7 = 0; var7 < var6; ++var7) {
                    TileMap area = var5[var7];
                    if (area.numplayers < Manager.getMapid(Map.arrLang[menuId - 1]).template.maxplayers) {
                        p.c.tileMap.leave(p);
                        area.EnterMap0(p.c);
                        return;
                    }
                }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcUmayaki_Truong(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
            case 1:
            case 2: {
                TileMap[] var5 = Manager.getMapid(Map.arrTruong[menuId]).area;
                int var6 = var5.length;
                for (int var7 = 0; var7 < var6; ++var7) {
                    TileMap area = var5[var7];
                    if (area.numplayers < Manager.getMapid(Map.arrTruong[menuId]).template.maxplayers) {
                        p.c.tileMap.leave(p);
                        area.EnterMap0(p.c);
                        return;
                    }
                }
                break;
            }
            case 3:
                Service.chatNPC(p, (short) npcid, "Ta kéo xe qua các trường, không qua quán net đâu!");
                return;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcToyotomi(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                switch (b3) {
                    case 0:
                        Server.manager.sendTB(p, "Top đại gia yên", Rank.getStringBXH(0));
                        return;
                    case 1:
                        Server.manager.sendTB(p, "Top cao thủ", Rank.getStringBXH(1));
                        return;
                    case 2:
                        Server.manager.sendTB(p, "Top gia tộc", Rank.getStringBXH(2));
                        return;
                    case 3:
                        Server.manager.sendTB(p, "Top hang động", Rank.getStringBXH(3));
                        return;
                    default:
                        return;
                }
            case 1:
                if (p.c.get().nclass > 0) {
                    Service.chatNPC(p, (short) npcid, "Con đã vào lớp từ trước rồi mà.");
                } else if (p.c.get().level < 10) {
                    Service.chatNPC(p, (short) npcid, "Con cần đạt trình độ cấp 10 mới có thể nhập học con nhé!");
                } else if (p.c.get().ItemBody[1] != null) {
                    Service.chatNPC(p, (short) npcid, "Con cần có 1 tâm hồn trong trắng mới có thể nhập học, hãy tháo vũ khí trên người ra!");
                } else if (p.c.getBagNull() < 2) {
                    Service.chatNPC(p, (short) npcid, "Hành trang cần phải có ít nhất 2 ô trống mới có thể nhập học!");
                } else {
                    if (b3 == 0) {
                        Admission.Admission(p, (byte) 1);
                    } else {
                        if (b3 != 1) {
                            Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                            break;
                        }
                        Admission.Admission(p, (byte) 2);
                    }
                    Service.chatNPC(p, (short) npcid, "Hãy chăm chỉ luyện tập, có làm thì mới có ăn con nhé.");
                }
                break;
            case 2:
                if (p.c.get().nclass != 1 && p.c.get().nclass != 2) {
                    Service.chatNPC(p, (short) npcid, "Con không phải học sinh của trường này, ta không thể giúp con tẩy điểm dược rồi.");
                } else if (b3 == 0) {
                    if (p.c.get().countTayTiemNang < 1) {
                        Service.chatNPC(p, (short) npcid, "Số lần tẩy điểm tiềm năng của con đã hết.");
                        return;
                    }

                    p.restPpoint();
                    --p.c.get().countTayTiemNang;
                    Service.chatNPC(p, (short) npcid, "Ta đã giúp con tẩy điểm tiềm năng, hãy nâng điểm thật hợp lý nha.");
                    p.sendAddchatYellow("Tẩy điểm tiềm năng thành công");
                } else if (b3 == 1) {
                    if (p.c.get().countTayKyNang < 1) {
                        Service.chatNPC(p, (short) npcid, "Số lần tẩy điểm kỹ năng của con đã hết.");
                        return;
                    }
                    p.restSpoint();
                    --p.c.get().countTayKyNang;
                    Service.chatNPC(p, (short) npcid, "Ta đã giúp con tẩy điểm kỹ năng, hãy nâng điểm thật hợp lý nha.");
                    p.sendAddchatYellow("Tẩy điểm kỹ năng thành công");
                }
                break;
            case 3:
                Service.chatNPC(p, (short) npcid, "Trường ta là 1 ngôi trường danh giá, chỉ giành cho nhưng ninja tính nóng như kem mà thôi.");
                break;
            case 4:
                Service.chatNPC(p, (short) npcid, "Ta đang hơi mệt xíu, ta sẽ giao chiến với con sau nha! Bye bye...");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcOokamesama(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                switch (b3) {
                    case 0:
                        Server.manager.sendTB(p, "Top đại gia yên", Rank.getStringBXH(0));
                        return;
                    case 1:
                        Server.manager.sendTB(p, "Top cao thủ", Rank.getStringBXH(1));
                        return;
                    case 2:
                        Server.manager.sendTB(p, "Top gia tộc", Rank.getStringBXH(2));
                        return;
                    case 3:
                        Server.manager.sendTB(p, "Top hang động", Rank.getStringBXH(3));
                        return;
                    default:
                        return;
                }
            case 1:
                if (p.c.get().nclass > 0) {
                    Service.chatNPC(p, (short) npcid, "Con đã vào lớp từ trước rồi mà.");
                } else if (p.c.get().level < 10) {
                    Service.chatNPC(p, (short) npcid, "Con cần đạt trình độ cấp 10 mới có thể nhập học con nhé!");
                } else if (p.c.get().ItemBody[1] != null) {
                    Service.chatNPC(p, (short) npcid, "Con cần có 1 tâm hồn trong trắng mới có thể nhập học, hãy tháo vũ khí trên người ra!");
                } else if (p.c.getBagNull() < 2) {
                    Service.chatNPC(p, (short) npcid, "Hành trang cần phải có ít nhất 2 ô trống mới có thể nhập học!");
                } else {
                    if (b3 == 0) {
                        Admission.Admission(p, (byte) 3);
                    } else {
                        if (b3 != 1) {
                            Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                            break;
                        }
                        Admission.Admission(p, (byte) 4);
                    }

                    Service.chatNPC(p, (short) npcid, "Hãy chăm chỉ luyện tập, có làm thì mới có ăn con nhé.");
                }
                break;
            case 2:
                if (p.c.get().nclass != 3 && p.c.get().nclass != 4) {
                    Service.chatNPC(p, (short) npcid, "Con không phải học sinh của trường này, ta không thể giúp con tẩy điểm dược rồi.");
                } else if (b3 == 0) {
                    if (p.c.get().countTayTiemNang < 1) {
                        Service.chatNPC(p, (short) npcid, "Số lần tẩy điểm tiềm năng của con đã hết.");
                        return;
                    }
                    p.restPpoint();
                    --p.c.get().countTayTiemNang;
                    Service.chatNPC(p, (short) npcid, "Ta đã giúp con tẩy điểm tiềm năng, hãy nâng điểm thật hợp lý nha.");
                    p.sendAddchatYellow("Tẩy điểm tiềm năng thành công");
                } else if (b3 == 1) {
                    if (p.c.get().countTayKyNang < 1) {
                        Service.chatNPC(p, (short) npcid, "Số lần tẩy điểm kỹ năng của con đã hết.");
                        return;
                    }

                    p.restSpoint();
                    --p.c.get().countTayKyNang;
                    Service.chatNPC(p, (short) npcid, "Ta đã giúp con tẩy điểm kỹ năng, hãy nâng điểm thật hợp lý nha.");
                    p.sendAddchatYellow("Tẩy điểm kỹ năng thành công");
                }
                break;
            case 3:
                Service.chatNPC(p, (short) npcid, "Sao hôm nay trời nóng thế nhỉ, hình như biến đổi khí hậu làm tan hết băng trường ta rồi!");
                break;
            case 4:
                Service.chatNPC(p, (short) npcid, "Ta đang hơi mệt xíu, ta sẽ giao chiến với con sau nha! Bye bye...");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

    public static void npcKazeto(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                switch (b3) {
                    case 0:

                        Server.manager.sendTB(p, "Top đại gia yên", Rank.getStringBXH(0));
                        return;
                    case 1:

                        Server.manager.sendTB(p, "Top cao thủ", Rank.getStringBXH(1));
                        return;
                    case 2:

                        Server.manager.sendTB(p, "Top gia tộc", Rank.getStringBXH(2));
                        return;
                    case 3:

                        Server.manager.sendTB(p, "Top hang động", Rank.getStringBXH(3));
                        return;
                    default:
                        return;
                }
            case 1:
                if (p.c.get().nclass > 0) {
                    Service.chatNPC(p, (short) npcid, "Con đã vào lớp từ trước rồi mà.");
                } else if (p.c.get().level < 10) {
                    Service.chatNPC(p, (short) npcid, "Con cần đạt trình độ cấp 10 mới có thể nhập học con nhé!");
                } else if (p.c.get().ItemBody[1] != null) {
                    Service.chatNPC(p, (short) npcid, "Con cần có 1 tâm hồn trong trắng mới có thể nhập học, hãy tháo vũ khí trên người ra!");
                } else if (p.c.getBagNull() < 2) {
                    Service.chatNPC(p, (short) npcid, "Hành trang cần phải có ít nhất 2 ô trống mới có thể nhập học!");
                } else {
                    if (b3 == 0) {
                        Admission.Admission(p, (byte) 5);
                    } else if (b3 == 1) {
                        Admission.Admission(p, (byte) 6);
                    }

                    Service.chatNPC(p, (short) npcid, "Hãy chăm chỉ luyện tập, có làm thì mới có ăn con nhé.");
                }
                break;
            case 2:
                if (p.c.get().nclass != 5 && p.c.get().nclass != 6) {
                    Service.chatNPC(p, (short) npcid, "Con không phải học sinh của trường này, ta không thể giúp con tẩy điểm dược rồi.");
                } else if (b3 == 0) {
                    if (p.c.get().countTayTiemNang < 1) {
                        Service.chatNPC(p, (short) npcid, "Số lần tẩy điểm tiềm năng của con đã hết.");
                        return;
                    }
                    p.restPpoint();
                    --p.c.get().countTayTiemNang;
                    Service.chatNPC(p, (short) npcid, "Ta đã giúp con tẩy điểm tiềm năng, hãy nâng điểm thật hợp lý nha.");
                    p.sendAddchatYellow("Tẩy điểm tiềm năng thành công");
                } else if (b3 == 1) {
                    if (p.c.get().countTayKyNang < 1) {
                        Service.chatNPC(p, (short) npcid, "Số lần tẩy điểm kỹ năng của con đã hết.");
                        return;
                    }
                    p.restSpoint();
                    --p.c.get().countTayKyNang;
                    Service.chatNPC(p, (short) npcid, "Ta đã giúp con tẩy điểm kỹ năng, hãy nâng điểm thật hợp lý nha.");
                    p.sendAddchatYellow("Tẩy điểm kỹ năng thành công");
                }
                break;
            case 3:
                Service.chatNPC(p, (short) npcid, "Ngươi là người thổi tan băng của trường Ookaza và mang kem về cho trường Hirosaki đúng không?");
                break;
            case 4:
                Service.chatNPC(p, (short) npcid, "Ta đang hơi mệt xíu, ta sẽ giao chiến với con sau nha! Bye bye...");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

    public static void npcTajima(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                Service.chatNPC(p, (short) npcid, "Chào mừng con đến với ngôi làng đi đâu cũng phải nhớ về!");
                break;
            case 1:
                Service.chatNPC(p, (short) npcid, "Đang cập nhật!");
                break;
            case 2:
                if (p.c.timeRemoveClone > System.currentTimeMillis()) {
                    p.toNhanBan();
                } else {
                    Service.chatNPC(p, (short) npcid, "Con không có phân thân để sử dụng chức năng này!");
                }
                break;
            case 3:
                if (!p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, "Con không phải phân thân để sử dụng chức năng này!");
                    return;
                }
                if (!p.c.clone.isDie && p.c.timeRemoveClone > System.currentTimeMillis()) {
                    p.exitNhanBan(true);
                }
                break;
            case 4:
                Server.manager.sendTB(p, "Top Điểm Nạp", Rank.getStringBXH(6));
                break;
            case 5:
                Server.manager.sendTB(p, "Top Điểm Hang Động", Rank.getStringBXH(7));
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "ERROR!");
                break;
            }
        }
    }

    public static void npcRei(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                Service.chatNPC(p, (short) npcid, "Ngươi đến đây làm gì, không có nhiệm vụ cho ngươi đâu!");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcKirin(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                Service.chatNPC(p, (short) npcid, "Ngươi đến đây làm gì, không có nhiệm vụ cho ngươi đâu!");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

    public static void npcSoba(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                Service.chatNPC(p, (short) npcid, "Ta sẽ sớm có nhiệm vụ cho con!");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcSunoo(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                Service.chatNPC(p, (short) npcid, "Khụ khụ...");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

    public static void npcGuriin(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcMatsurugi(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcOkanechan(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                switch (b3) {
                    case 0:
                        Service.sendInputDialog(p, (short) 12, "Đổi Lượng Ra Yên");
                        break;
                }
                break;
            case 1: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (b3) {
                    case 0:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 10 && p.c.checkLevel[0] == 0) { // quà level 10
                            p.c.addItemBag(false, ItemTemplate.itemDefault(222, true));
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 1000, " Nhận Qùa Thăng Cấp Level 10", +1000);
                            p.upluongMessage(1000L);
                            p.c.checkLevel[0] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 1:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 20 && p.c.checkLevel[1] == 0) {
                            p.c.addItemBag(false, ItemTemplate.itemDefault(223, true));
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 2000, " Nhận Qùa Thăng Cấp Level 20", +2000);
                            p.upluongMessage(2000L);
                            p.c.checkLevel[1] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 2:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 30 && p.c.checkLevel[2] == 0) {
                            p.c.addItemBag(false, ItemTemplate.itemDefault(224, true));
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 3000, " Nhận Qùa Thăng Cấp Level 30", +3000);
                            p.upluongMessage(3000L);
                            p.c.checkLevel[2] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 3:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }

                        if (p.c.level >= 40 && p.c.checkLevel[3] == 0) {
                            p.c.addItemBag(false, ItemTemplate.itemDefault(225, true));
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 4000, " Nhận Qùa Thăng Cấp Level 40", +4000);
                            p.upluongMessage(4000L);
                            p.c.checkLevel[3] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 4:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 50 && p.c.checkLevel[4] == 0) {
                            p.c.addItemBag(false, ItemTemplate.itemDefault(226, true));
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 5000, " Nhận Qùa Thăng Cấp Level 50", +5000);
                            p.upluongMessage(5000L);
                            p.c.checkLevel[4] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 5:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 60 && p.c.checkLevel[5] == 0) {
                            p.c.addItemBag(false, ItemTemplate.itemDefault(227, true));
                            Item itemID = ItemTemplate.itemDefault(396 + p.c.nclass);
                            itemID.isLock = true;
                            p.c.addItemBag(false, itemID);
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 6000, " Nhận Qùa Thăng Cấp Level 60", +6000);
                            p.upluongMessage(6000L);
                            p.c.checkLevel[5] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 6:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 70 && p.c.checkLevel[6] == 0) {
                            p.c.addItemBag(false, ItemTemplate.itemDefault(228, true));
                            LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 7000, " Nhận Qùa Thăng Cấp Level 20", +7000);
                            p.upluongMessage(7000L);
                            p.c.checkLevel[6] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                    case 7:
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.level >= 150 && p.c.checkLevel[7] == 0) {
//                            Item it = ItemTemplate.itemDefault(p.c.gender == 1 ? 834 : 833);
//                            p.c.addItemBag(false, it);
                            p.upluongMessage(40000L);
                            p.c.checkLevel[7] = 1;
                            Service.chatNPC(p, (short) npcid, "Chúc mừng con đã đạt đến cấp độ mới!");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Trình độ của con không đủ hoặc con đã nhận thưởng rồi!");
                        }
                        break;
                }
                break;
            }
            case 2:
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                Service.sendInputDialog(p, (short) 4, "Nhập Mã Quà Tặng");
                break;
            case 3:
                switch (b3) {
                    case 0:
                        Service.sendInputDialog(p, (short) 9, "Nhập Số Lượng ( Tỉ Lệ : 10.000 Coin = 10.000 Lượng)");
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) 10, "Nhập Số Lượng ( Tỉ Lệ : 10.000 Coin = 10.000.000 Xu)");
                        break;
                }
                break;
            case 4:
                Server.manager.sendTB(p, "Nạp Coin", "Để Nạp Coin Vui Lòng Lên Trang Chủ Nsokey.top Hoặc Liên Hệ Zalo Admin !");
                break;
            case 5:
                try {
                synchronized (Server.LOCK_MYSQL) {
                    ResultSet red = SQLManager.stat.executeQuery("SELECT `coin` FROM `player` WHERE `id` = " + p.id + ";");
                    if (red != null && red.first()) {
                        p.coin = red.getInt("coin");
                        Service.chatNPC(p, (short) npcid, "Số Coin Hiện Có : " + p.coin);
                        break;
                    }
                }
            } catch (SQLException var17) {
                p.conn.sendMessageLog("Lỗi.");
            }
            break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcRikudou(Player p, byte npcid, byte menuId, byte b3) {
        MapTemplate map;
        MobTemplate mob;
        switch (menuId) {
            case 0: {
                Service.chatNPC(p, (short) npcid, "Hãy chăm chỉ lên nha.");
                break;
            }
            case 1: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.level < 20) {
                            Service.chatNPC(p, (short) npcid, "Con cần đạt cấp độ 20 để có thể nhận nhiệm vụ.");
                            return;
                        }
                        if (p.c.isTaskHangNgay != 0) {
                            Service.chatNPC(p, (short) npcid, "Ta đã giao nhiệm vụ cho con trước đó rồi");
                            return;
                        }
                        if (p.c.countTaskHangNgay >= 20) {
                            Service.chatNPC(p, (short) npcid, "Con đã hoàn thành hết nhiệm vụ ngày hôm nay rồi, ngày mai hãy quay lại nha.");
                            return;
                        }
                        mob = Service.getMobIdByLevel(p.c.level);
                        if (mob != null) {
                            map = Service.getMobMapId(mob.id);
                            if (map != null) {
                                p.c.taskHangNgay[0] = 0;
                                p.c.taskHangNgay[1] = 0;
                                p.c.taskHangNgay[2] = Util.nextInt(70, 100);
                                p.c.taskHangNgay[3] = mob.id;
                                p.c.taskHangNgay[4] = map.id;
                                p.c.isTaskHangNgay = 1;
                                p.c.countTaskHangNgay++;
                                Service.getTaskOrder(p.c, (byte) 0);
                                Service.chatNPC(p, (short) npcid, "Đây là nhiệm vụ thứ " + p.c.countTaskHangNgay + "/20 trong ngày của con.");
                            }
                        }
                        break;
                    }

                    case 1: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.isTaskHangNgay == 0) {
                            Service.chatNPC(p, (short) npcid, "Con chưa nhận nhiệm vụ nào cả!");
                            return;
                        }
                        p.c.isTaskHangNgay = 0;
                        p.c.countTaskHangNgay--;
                        p.c.taskHangNgay = new int[]{-1, -1, -1, -1, -1, 0, p.c.countTaskHangNgay};
                        Service.clearTaskOrder(p.c, (byte) 0);
                        Service.chatNPC(p, (short) npcid, "Con đã huỷ nhiệm vụ lần này.");
                        break;
                    }
                    case 2: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.isTaskHangNgay == 0) {
                            Service.chatNPC(p, (short) npcid, "Con chưa nhận nhiệm vụ nào cả!");
                            return;
                        }

                        if (p.c.getBagNull() == 0) {
                            p.conn.sendMessageLog(Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (p.c.taskHangNgay[1] < p.c.taskHangNgay[2]) {
                            Service.chatNPC(p, (short) npcid, "Con chưa hoàn thành nhiệm vụ ta giao!");
                            return;
                        }
                        p.c.isTaskHangNgay = 0;
                        p.c.taskHangNgay = new int[]{-1, -1, -1, -1, -1, 0, p.c.countTaskHangNgay};
                        Service.clearTaskOrder(p.c, (byte) 0);
                        p.upluongMessage(Util.nextInt(500, 1000));
//                        if (p.c.countTaskHangNgay == 20) {
//                            Service.addItemToBagNinja(p.c, ItemName.CHIA_KHOA_VE_BUA, false, true, 5, false, -1);
//                        }
                        if (!p.c.clan.clanName.isEmpty()) {
                            p.c.p.upExpClan(Util.nextInt(10, 50));
                        }
                        if (p.c.pointUydanh < 5000) {
                            p.c.pointUydanh += 1;
                        }
                        Service.chatNPC(p, (short) npcid, "Con hãy nhận lấy phần thưởng của mình.");
                        break;
                    }
                    case 3: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.taskHangNgay[4] != -1) {
                            Map ma = Manager.getMapid(p.c.taskHangNgay[4]);
                            int var8;
                            TileMap area;
                            for (var8 = 0; var8 < ma.area.length; ++var8) {
                                area = ma.area[var8];
                                if (area.numplayers < ma.template.maxplayers) {
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                            }
                        }
                        Service.chatNPC(p, (short) npcid, "Con chưa nhận nhiệm vụ nào cả!");
                        break;
                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            case 2: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.level < 30) {
                            Service.chatNPC(p, (short) npcid, "Con cần đạt cấp độ 30 để có thể nhận nhiệm vụ tà thú.");
                            return;
                        }

                        if (p.c.isTaskTaThu != 0) {
                            Service.chatNPC(p, (short) npcid, "Ta đã giao nhiệm vụ cho con trước đó rồi");
                            return;
                        }

                        if (p.c.countTaskTaThu >= 2) {
                            Service.chatNPC(p, (short) npcid, "Con đã hoàn thành hết nhiệm vụ ngày hôm nay rồi, ngày mai hãy quay lại nha.");
                            return;
                        }
                        mob = Service.getMobIdTaThu(p.c.level);
                        if (mob != null) {
                            map = Service.getMobMapIdTaThu(mob.id);
                            if (map != null) {
                                p.c.taskTaThu[0] = 1;
                                p.c.taskTaThu[1] = 0;
                                p.c.taskTaThu[2] = 1;
                                p.c.taskTaThu[3] = mob.id;
                                p.c.taskTaThu[4] = map.id;
                                p.c.isTaskTaThu = 1;
                                ++p.c.countTaskTaThu;
                                Service.getTaskOrder(p.c, (byte) 1);
                                Service.chatNPC(p, (short) npcid, "Hãy hoàn thành nhiệm vụ và trở về đây nhận thưởng.");
                            }
                        }
                        break;
                    }
                    case 1: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.isTaskTaThu == 0) {
                            Service.chatNPC(p, (short) npcid, "Con chưa nhận nhiệm vụ nào cả!");
                            return;
                        }
                        Service.clearTaskOrder(p.c, (byte) 1);
                        p.c.isTaskTaThu = 0;
                        --p.c.countTaskTaThu;
                        p.c.taskTaThu = new int[]{-1, -1, -1, -1, -1, 0, p.c.countTaskTaThu};
                        Service.chatNPC(p, (short) npcid, "Con đã huỷ nhiệm vụ lần này.");
                        break;
                    }

                    case 2: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }

                        if (p.c.isTaskTaThu == 0) {
                            Service.chatNPC(p, (short) npcid, "Con chưa nhận nhiệm vụ nào cả!");
                            return;
                        }

                        if (p.c.taskTaThu[1] < p.c.taskTaThu[2]) {
                            Service.chatNPC(p, (short) npcid, "Con chưa hoàn thành nhiệm vụ ta giao!");
                            return;
                        }

                        if (p.c.getBagNull() < 2) {
                            Service.chatNPC(p, (short) npcid, "Hành trang của con không đủ chỗ trống để nhận thưởng");
                            return;
                        }

                        p.c.isTaskTaThu = 0;
                        p.c.taskTaThu = new int[]{-1, -1, -1, -1, -1, 0, p.c.countTaskTaThu};
                        Service.clearTaskOrder(p.c, (byte) 1);
                        if (p.c.pointUydanh < 5000) {
                            p.c.pointUydanh += 2;
                        }
                        p.upluongMessage(Util.nextInt(50, 100));
                        Service.addItemToBagNinja(p.c, ItemName.MANH_GIAY_VUN, false, false, 2, false, -1);
                        Service.chatNPC(p, (short) npcid, "Con hãy nhận lấy phần thưởng của mình.");
                        break;
                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            case 3: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, Short.valueOf(npcid), Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (ChienTruong.chienTruong == null) {
                            Service.chatNPC(p, Short.valueOf(npcid), "Chiến trường chưa được tổ chức.");
                            return;
                        }
                        if (ChienTruong.chienTruong != null) {
                            if (ChienTruong.chienTruong30 && (p.c.level < 30 || p.c.level >= 50)) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Bây giờ là thời gian chiến trường cho cấp độ từ 30 đến 49. Trình độ của con không phù hợp để tham gia.");
                                return;
                            }
                            if (ChienTruong.chienTruong50 && p.c.level < 50) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Bây giờ là thời gian chiến trường cho cấp độ lớn hơn hoặc bằng 50. Trình độ của con không phù hợp để tham gia.");
                                return;
                            }
                            if ((ChienTruong.chienTruong30 || ChienTruong.chienTruong50) && p.c.pheCT == 1) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Con đã điểm danh phe Hắc giả trước đó rồi.");
                                return;
                            }
                            if (ChienTruong.start && p.c.pheCT == -1) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Chiến trường đã bắt đầu, không thể báo danh.");
                                return;
                            }
                            if ((ChienTruong.chienTruong30 || ChienTruong.chienTruong50) && p.c.pheCT == -1) {
                                if (p.c.pointUydanh < 5000) {
                                    p.c.pointUydanh += 10;
                                }
                                p.c.pheCT = 0;
                                p.c.pointCT = 0;
                                p.c.isTakePoint = 0;
                                p.c.typepk = (byte) 4;
                                Service.ChangTypePkId(p.c, (byte) 4);
                                Service.updatePointCT(p.c, 0);
                                if (p.c.party != null) {
                                    p.c.party.removePlayer(p.c.id);
                                }
                                if (!ChienTruong.bxhCT.containsKey(p.c)) {
                                    ChienTruong.bxhCT.put(p.c, p.c.pointCT);
                                } else {
                                    ChienTruong.bxhCT.replace(p.c, p.c.pointCT);
                                }
                                Map ma = Manager.getMapid(ChienTruong.chienTruong.map[0].id);
                                for (TileMap area : ma.area) {
                                    if (area.numplayers >= ma.template.maxplayers) {
                                        continue;
                                    }
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                                return;
                            }
                            p.c.typepk = (byte) 4;
                            Service.ChangTypePkId(p.c, (byte) 4);
                            Service.updatePointCT(p.c, 0);
                            if (p.c.party != null) {
                                p.c.party.removePlayer(p.c.id);
                            }
                            if (!ChienTruong.bxhCT.containsKey(p.c)) {
                                ChienTruong.bxhCT.put(p.c, p.c.pointCT);
                            } else {
                                ChienTruong.bxhCT.replace(p.c, p.c.pointCT);
                            }
                            Map ma = Manager.getMapid(ChienTruong.chienTruong.map[0].id);
                            for (TileMap area : ma.area) {
                                if (area.numplayers >= ma.template.maxplayers) {
                                    continue;
                                }
                                p.c.tileMap.leave(p);
                                area.EnterMap0(p.c);
                                return;
                            }
                        }
                        return;
                    }
                    case 1: {
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, Short.valueOf(npcid), Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (ChienTruong.chienTruong == null) {
                            Service.chatNPC(p, Short.valueOf(npcid), "Chiến trường chưa được tổ chức.");
                            return;
                        }
                        if (ChienTruong.chienTruong != null) {
                            if (ChienTruong.chienTruong30 && (p.c.level < 30 || p.c.level >= 50)) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Bây giờ là thời gian chiến trường cho cấp độ từ 30 đến 49. Trình độ của con không phù hợp để tham gia.");
                                return;
                            }
                            if (ChienTruong.chienTruong50 && p.c.level < 50) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Bây giờ là thời gian chiến trường cho cấp độ lớn hơn hoặc bằng 50. Trình độ của con không phù hợp để tham gia.");
                                return;
                            }
                            if (ChienTruong.start && p.c.pheCT == -1) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Chiến trường đã bắt đầu, không thể báo danh.");
                                return;
                            }
                            if ((ChienTruong.chienTruong30 || ChienTruong.chienTruong50) && p.c.pheCT == 0) {
                                Service.chatNPC(p, Short.valueOf(npcid), "Con đã điểm danh phe Bạch giả trước đó rồi.");
                                return;
                            }
                            if ((ChienTruong.chienTruong30 || ChienTruong.chienTruong50) && p.c.pheCT == -1) {
                                if (p.c.pointUydanh < 5000) {
                                    p.c.pointUydanh += 10;
                                }
                                p.c.pheCT = 1;
                                p.c.pointCT = 0;
                                p.c.typepk = (byte) 5;
                                p.c.isTakePoint = 0;
                                Service.ChangTypePkId(p.c, (byte) 5);
                                Service.updatePointCT(p.c, 0);
                                if (p.c.party != null) {
                                    p.c.party.removePlayer(p.c.id);
                                }
                                if (!ChienTruong.bxhCT.containsKey(p.c)) {
                                    ChienTruong.bxhCT.put(p.c, p.c.pointCT);
                                } else {
                                    ChienTruong.bxhCT.replace(p.c, p.c.pointCT);
                                }
                                Map ma = Manager.getMapid(ChienTruong.chienTruong.map[6].id);
                                for (TileMap area : ma.area) {
                                    if (area.numplayers >= ma.template.maxplayers) {
                                        continue;
                                    }
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                                return;
                            }
                            p.c.typepk = (byte) 5;
                            Service.ChangTypePkId(p.c, (byte) 5);
                            Service.updatePointCT(p.c, 0);
                            if (p.c.party != null) {
                                p.c.party.removePlayer(p.c.id);
                            }
                            if (!ChienTruong.bxhCT.containsKey(p.c)) {
                                ChienTruong.bxhCT.put(p.c, p.c.pointCT);
                            } else {
                                ChienTruong.bxhCT.replace(p.c, p.c.pointCT);
                            }
                            Map ma = Manager.getMapid(ChienTruong.chienTruong.map[6].id);
                            for (TileMap area : ma.area) {
                                if (area.numplayers >= ma.template.maxplayers) {
                                    continue;
                                }
                                p.c.tileMap.leave(p);
                                area.EnterMap0(p.c);
                                return;
                            }
                        }
                        return;
                    }
                    case 2: {
                        if (ChienTruong.finish) {
                            Service.evaluateCT(p.c);
                            break;
                        }
                        Server.manager.sendTB(p, "Kết quả", "Chưa có thông tin.");
                        break;
                    }
                    case 3: {
                        Server.manager.sendTB(p, "Hướng dẫn", "Chiến trường được mở 2 lần mỗi ngày.\n- Chiến trường lv30: giành cho nhân vật level từ 30 đến 45, điểm danh vào lúc 16h và bắt đầu từ 16h30' đến 17h30'.\n- Chiến trường lv50: giành cho nhân vật level từ 50 trở lên, điểm danh vào lúc 19h và bắt đầu từ 19h30' đến 20h30'.\n\n+ Top1: 10v đan mỗi loại + 3tr xu.\n+ Top 2: 7v đan mỗi loại + 2tr xu.\n+ Top 3: 5v đan mỗi loại + 1tr xu.\n+ Phe thắng: 1v đan mỗi loại + 500k xu.");
                        break;
                    }
                }
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

    public static void npcGoosho(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
                p.requestItem(14);
                break;
            case 1:
                p.requestItem(15);
                break;
            case 2:
                p.requestItem(32);
                break;
            case 3:
                p.requestItem(34);
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcTruCoQuan(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.c.quantityItemyTotal(260) < 1) {
                    p.sendAddchatYellow("Không có chìa khoá để mở cửa này.");
                    return;
                }
                if (p.c.tileMap.map.lanhDiaGiaToc != null && p.c.tileMap.map.mapLDGT()) {
                    switch (p.c.tileMap.map.id) {
                        case 80: {

                            p.c.tileMap.map.lanhDiaGiaToc.openMap(1, p);
                            break;
                        }
                        case 81: {

                            p.c.tileMap.map.lanhDiaGiaToc.openMap(2, p);
                            break;
                        }
                        case 82: {

                            p.c.tileMap.map.lanhDiaGiaToc.openMap(3, p);
                            break;
                        }
                        case 83: {

                            p.c.tileMap.map.lanhDiaGiaToc.openMap(4, p);
                            break;
                        }
                        case 84: {

                            p.c.tileMap.map.lanhDiaGiaToc.openMap(5, p);
                            break;
                        }
                        case 85: {
                            p.c.tileMap.map.lanhDiaGiaToc.openMap(6, p);
                            break;
                        }
                        case 86: {
                            p.c.tileMap.map.lanhDiaGiaToc.openMap(7, p);
                            break;
                        }
                        case 87: {
                            p.c.tileMap.map.lanhDiaGiaToc.openMap(8, p);
                            Server.manager.sendTB(p, "Ghi chú", "Con đường này sẽ dẫn đến cánh cửa nơi ở của một nhân vật huyền bí đã bị lời nguyền cổ "
                                    + "xưa yểm bùa rằng sẽ không ai có thể đánh bại được nhân vật huyền bí này. Bạn hãy mau tìm cách hoá giải lời nguyền.");
                            break;
                        }
                        case 88: {
                            p.c.tileMap.map.lanhDiaGiaToc.openMap(9, p);
                            Server.manager.sendTB(p, "Ghi chú", "Con đường này sẽ dẫn đến cánh cửa nơi ở của một nhân vật huyền bí đã bị lời nguyền cổ "
                                    + "xưa yểm bùa rằng sẽ không ai có thể đánh bại được nhân vật huyền bí này. Bạn hãy mau tìm cách hoá giải lời nguyền.");
                            break;
                        }
                        case 89: {
                            p.c.tileMap.map.lanhDiaGiaToc.openMap(10, p);
                            Server.manager.sendTB(p, "Ghi chú", "Con đường này sẽ dẫn đến cánh cửa nơi ở của một nhân vật huyền bí đã bị lời nguyền cổ "
                                    + "xưa yểm bùa rằng sẽ không ai có thể đánh bại được nhân vật huyền bí này. Bạn hãy mau tìm cách hoá giải lời nguyền.");
                            break;
                        }
                        default: {
                            break;
                        }

                    }
                }
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcShinwa(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                if (p.c.level < 60) {
                    p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 60");
                    return;
                }
                p.menuIdAuction = b3;
                final List<ShinwaTemplate> itemShinwas = ShinwaManager.entrys.get((int) b3);
                final Message mess = new Message(103);
                mess.writer().writeByte(b3);
                if (itemShinwas != null) {
                    mess.writer().writeInt(itemShinwas.size());
                    ShinwaTemplate item;
                    for (int i = 0; i < itemShinwas.size(); i++) {
                        item = itemShinwas.get(i);
                        if (item != null) {
                            mess.writer().writeInt(i);
                            mess.writer().writeInt(item.getRemainTime());
                            mess.writer().writeShort(item.getItem().quantity);
                            mess.writer().writeUTF(item.getSeller());
                            mess.writer().writeInt((int) item.getPrice());
                            mess.writer().writeShort(item.getItem().id);
                        } else {
                            mess.writer().writeInt(i);
                            mess.writer().writeInt(-1);
                            mess.writer().writeShort(0);
                            mess.writer().writeUTF("");
                            mess.writer().writeInt(999999999);
                            mess.writer().writeShort(12);
                        }
                    }
                } else {
                    mess.writer().writeInt(0);
                }
                mess.writer().flush();
                p.conn.sendMessage(mess);
                mess.cleanup();
                break;
            }
            case 1: {
                if (p.c.level < 69) {
                    p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 69");
                    return;
                }
                p.menuIdAuction = -2;
                p.requestItem(36);
                break;
            }
            case 2: {
                if (p.c.level < 69) {
                    p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 69");
                    return;
                }
                try {
                    synchronized (ShinwaManager.entrys.get((int) -1)) {
                        List<ShinwaTemplate> itemShinwas = ShinwaManager.entrys.get((int) -1);
                        List<ShinwaTemplate> list = new ArrayList<>();
                        boolean flag = false;
                        for (ShinwaTemplate item : itemShinwas) {
                            if (item.getSeller().equals(p.c.name)) {
                                if (p.c.getBagNull() == 0) {
                                    flag = true;
                                    break;
                                }
                                p.c.addItemBag(true, item.getItem());
                                list.add(item);
                            }
                        }
                        if (flag) {
                            Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống để nhận lại vật phẩm!");
                        } else if (list.isEmpty()) {
                            Service.chatNPC(p, (short) npcid, "Con không có đồ để nhận lại!");
                            return;
                        }
                        itemShinwas.removeAll(list);
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                    p.conn.sendMessageLog(e.getMessage());
                }
                break;
            }

        }
    }

    public static void npcChiHang(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcRakkii(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                p.requestItem(38);
                break;
            }
            case 1: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                Service.sendInputDialog(p, (short) 4, "Nhập Gift Code tại đây");
                break;
            }
            case 2: {
                switch (b3) {
                    case 0:
                    case 1: {
                        Server.manager.rotationluck[0].luckMessage(p);
                        return;
                    }
                    case 2: {
                        Server.manager.sendTB(p, "Vòng Xoay Vip", " ");
                        return;
                    }
                    default: {
                        return;
                    }
                }
            }
            case 3: {
                switch (b3) {
                    case 0:
                    case 1: {
                        Server.manager.rotationluck[1].luckMessage(p);
                        return;
                    }
                    case 2: {
                        Server.manager.sendTB(p, "Vòng Xoay May Mắn Lượng ", " ");
                        return;
                    }
                    default: {
                        return;
                    }
                }
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }

    }

 public static void npcKagai(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 1: {
                switch (b3) {
                    case 0: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.clan.clanName.isEmpty()) {
                            Service.chatNPC(p, (short) npcid, "Con chưa có Gia tộc.");
                            return;
                        }
                        if (p.c.clan != null && p.c.clan.typeclan != 4) {
                            Service.chatNPC(p, (short) npcid, "Con không phải tộc trưởng, không thể mời gia tộc chiến.");
                            return;
                        }
                        Service.sendInputDialog(p, (short) 5, "Nhập tên gia tộc đối phương");
                        break;
                    }
                    case 1: {
                        ClanManager clan = ClanManager.getClanName(p.c.clan.clanName);
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.clan.clanName.isEmpty()) {
                            Service.chatNPC(p, (short) npcid, "Con chưa có Gia tộc.");
                            return;
                        }
                        if (clan.gtcID == -1 || clan.typepkclan == 0) {
                            Service.chatNPC(p, (short) npcid, "Chưa diễn ra Gia Tộc Chiến.");
                            return;
                        }
                        if (GiaTocChien.gtcs.containsKey(clan.gtcID)) {
                            GiaTocChien giaTocChien = GiaTocChien.gtcs.get(clan.gtcID);
                            if (giaTocChien != null && giaTocChien.map[1] != null && giaTocChien.map[2] != null) {
                                p.c.typepk = (byte) ClanManager.getClanName(p.c.clan.clanName).typepkclan;
                                Service.ChangTypePkId(p.c, (byte) ClanManager.getClanName(p.c.clan.clanName).typepkclan);
                                Service.sendPointGTC(p.c, 0);
                                if (p.c.party != null) {
                                    p.c.party.removePlayer(p.c.id);
                                }
                                p.c.tileMap.leave(p);
                                giaTocChien.map[p.c.typepk - 3].area[0].EnterMap0(p.c);
                                return;
                            }
                        }

                    }
                    default: {
                        break;
                    }
                }
                break;
            }
            case 4: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    break;
                } else {
                    switch (b3) {
                        case 0: {
                            p.requestItem(43);
                            break;
                        }
                        case 1: {
                            p.requestItem(44);
                            break;
                        }
                        case 2: {
                            p.requestItem(45);
                            break;
                        }
                        case 3: {
                            Server.manager.sendTB(p, "Hướng dẫn", "");
                            break;
                        }
                        default: {
                            Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                            break;
                        }
                    }
                }
                break;
            }
            case 3: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    break;
                }
                switch (b3) {
                    case 0: {
                        Item Non = p.c.ItemBody[0];
                        if (Non == null) {
                            p.sendAddchatYellow("Hãy Mặc Nón Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Non.upgrade < 16) {
                            p.sendAddchatYellow("Nón +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Non == 1) {
                            p.sendAddchatYellow("Nón Đã Được Thức Tỉnh");
                            return;
                        }
                        Non.options.add(new Option(58, 5));
                        Non.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Non = 1;
                        p.c.removeItemBody((byte) 0);
                        p.c.addItemBag(true, Non);
                        break;
                    }
                    case 1: {
                        int i;
                        Item VuKhi = p.c.ItemBody[1];
                        if (VuKhi == null) {
                            p.sendAddchatYellow("Hãy Mặc Vũ Khí Vào Trước ");
                            return;
                        }
                        if (VuKhi.upgrade < 16) {
                            p.sendAddchatYellow("Vũ Khí +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        for (i = 0; i < VuKhi.options.size(); ++i) {
                            if (VuKhi.options.get(i).id != 85 && VuKhi.options.get(i).id == 85 && VuKhi.options.get(i).param < 9) {
                                Service.chatNPC(p, (short) npcid, "Vũ Khí Phải Đạt Tinh Luyện 9 Mới Có Thể Thức Tỉnh.");
                                return;
                            }
                        }
                        if (p.c.xu < 800000000) {
                            p.sendAddchatYellow("Cần 800.000.000 Xu");
                            return;
                        }
                        if (p.luong < 80000) {
                            p.sendAddchatYellow("Cần 80000 Lượng");
                            return;
                        }

                        if (p.c.VuKhi == 1) {
                            p.sendAddchatYellow("Vũ Khí Đã Được Thức Tỉnh");
                            return;
                        }
                        VuKhi.options.add(new Option(58, 10));
                        VuKhi.options.add(new Option(57, 100));
                        VuKhi.options.add(new Option(113, 2000));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-800000000);
                        p.upluongMessage(-80000);
                        p.c.VuKhi = 1;
                        p.c.removeItemBody((byte) 1);
                        p.c.addItemBag(true, VuKhi);
                        break;
                    }
                    case 2: {
                        Item Ao = p.c.ItemBody[2];
                        if (Ao == null) {
                            p.sendAddchatYellow("Hãy Mặc Áo Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Ao.upgrade < 16) {
                            p.sendAddchatYellow("Áo +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Ao == 1) {
                            p.sendAddchatYellow("Áo Đã Được Thức Tỉnh");
                            return;
                        }
                        Ao.options.add(new Option(58, 5));
                        Ao.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Ao = 1;
                        p.c.removeItemBody((byte) 2);
                        p.c.addItemBag(true, Ao);
                        break;
                    }
                    case 3: {
                        Item DayChuyen = p.c.ItemBody[3];
                        if (DayChuyen == null) {
                            p.sendAddchatYellow("Hãy Mặc Dây Chuyền Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (DayChuyen.upgrade < 16) {
                            p.sendAddchatYellow("Dây Chuyền +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.DayChuyen == 1) {
                            p.sendAddchatYellow("Dây Chuyền Đã Được Thức Tỉnh");
                            return;
                        }
                        DayChuyen.options.add(new Option(58, 5));
                        DayChuyen.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.DayChuyen = 1;
                        p.c.removeItemBody((byte) 3);
                        p.c.addItemBag(true, DayChuyen);
                        break;
                    }
                    case 4: {
                        Item Gang = p.c.ItemBody[4];
                        if (Gang == null) {
                            p.sendAddchatYellow("Hãy Mặc Găng Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Gang.upgrade < 16) {
                            p.sendAddchatYellow("Găng +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Gang == 1) {
                            p.sendAddchatYellow("Găng Đã Được Thức Tỉnh");
                            return;
                        }
                        Gang.options.add(new Option(58, 5));
                        Gang.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Gang = 1;
                        p.c.removeItemBody((byte) 4);
                        p.c.addItemBag(true, Gang);
                        break;
                    }
                    case 5: {
                        Item Nhan = p.c.ItemBody[5];
                        if (Nhan == null) {
                            p.sendAddchatYellow("Hãy Mặc Nhẫn Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Nhan.upgrade < 16) {
                            p.sendAddchatYellow("Nhẫn +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Nhan == 1) {
                            p.sendAddchatYellow("Nhẫn Đã Được Thức Tỉnh");
                            return;
                        }
                        Nhan.options.add(new Option(58, 5));
                        Nhan.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Nhan = 1;
                        p.c.removeItemBody((byte) 5);
                        p.c.addItemBag(true, Nhan);
                        break;
                    }
                    case 6: {
                        Item Quan = p.c.ItemBody[6];
                        if (Quan == null) {
                            p.sendAddchatYellow("Hãy Mặc Quần Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Quan.upgrade < 16) {
                            p.sendAddchatYellow("Quần +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Quan == 1) {
                            p.sendAddchatYellow("Quần Đã Được Thức Tỉnh");
                            return;
                        }
                        Quan.options.add(new Option(58, 5));
                        Quan.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Quan = 1;
                        p.c.removeItemBody((byte) 6);
                        p.c.addItemBag(true, Quan);
                        break;
                    }
                    case 7: {
                        Item Boi = p.c.ItemBody[7];
                        if (Boi == null) {
                            p.sendAddchatYellow("Hãy Mặc Bội Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Boi.upgrade < 16) {
                            p.sendAddchatYellow("Bội +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Boi == 1) {
                            p.sendAddchatYellow("Bội Đã Được Thức Tỉnh");
                            return;
                        }
                        Boi.options.add(new Option(58, 5));
                        Boi.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Boi = 1;
                        p.c.removeItemBody((byte) 7);
                        p.c.addItemBag(true, Boi);
                        break;
                    }
                    case 8: {
                        Item Giay = p.c.ItemBody[8];
                        if (Giay == null) {
                            p.sendAddchatYellow("Hãy Mặc Giày Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Giay.upgrade < 16) {
                            p.sendAddchatYellow("Giày +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Giay == 1) {
                            p.sendAddchatYellow("Giày Đã Được Thức Tỉnh");
                            return;
                        }
                        Giay.options.add(new Option(58, 5));
                        Giay.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Giay = 1;
                        p.c.removeItemBody((byte) 8);
                        p.c.addItemBag(true, Giay);
                        break;
                    }
                    case 9: {
                        Item Bua = p.c.ItemBody[9];
                        if (Bua == null) {
                            p.sendAddchatYellow("Hãy Mặc Bùa Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 400000000) {
                            p.sendAddchatYellow("Cần 400.000.000 Xu");
                            return;
                        }
                        if (p.luong < 40000) {
                            p.sendAddchatYellow("Cần 40000 Lượng");
                            return;
                        }
                        if (Bua.upgrade < 16) {
                            p.sendAddchatYellow("Bùa +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.Bua == 1) {
                            p.sendAddchatYellow("Bùa Đã Được Thức Tỉnh");
                            return;
                        }
                        Bua.options.add(new Option(58, 5));
                        Bua.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-400000000);
                        p.upluongMessage(-40000);
                        p.c.Bua = 1;
                        p.c.removeItemBody((byte) 9);
                        p.c.addItemBag(true, Bua);
                        break;
                    }
                    case 10:
                        Service.chatNPC(p, (short) npcid, "ChưaCóHướngDẫnĐâu!");
                        break;
                }
                break;
            }

            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }
// thêm npc
 public static void dunghop(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (b3) {
                    case 0:
                        byte i;
                        Item VuKhi = p.c.ItemBody[1];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[1] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang vũ khí");
                            return;
                        }
                        if (p.c.get().ItemBody[1].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Vũ khí phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < VuKhi.options.size(); ++i) {
                             if (VuKhi.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        VuKhi.options.add(new Option(153, 0));
                        VuKhi.options.add(new Option(58, 15));
                        VuKhi.options.add(new Option(57, 15));
                        p.sendAddchatYellow("Dung hợp vũ khí thành công !");
                        p.c.removeItemBody((byte) 1);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, VuKhi);
                       break;
                case 1:
                        Item Non = p.c.get().ItemBody[0];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[0] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang nón");
                            return;
                        }
                        if (p.c.get().ItemBody[0].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Nón phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Non.options.size(); ++i) {
                             if (Non.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Non.options.add(new Option(153, 0));
                        Non.options.add(new Option(58, 10));
                        Non.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp nón thành công !");
                        p.c.removeItemBody((byte) 0);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Non);
                       break;
                       case 2:
                        Item DayChuyen = p.c.get().ItemBody[3];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[3] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang dây chuyền");
                            return;
                        }
                        if (p.c.get().ItemBody[3].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Dây chuyền phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < DayChuyen.options.size(); ++i) {
                             if (DayChuyen.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        DayChuyen.options.add(new Option(153, 0));
                        DayChuyen.options.add(new Option(58, 10));
                        DayChuyen.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp dây chuyền thành công !");
                        p.c.removeItemBody((byte) 3);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, DayChuyen);
                       break;
                       case 3:
                        Item Ao = p.c.get().ItemBody[2];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[2] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang áo");
                            return;
                        }
                        if (p.c.get().ItemBody[2].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Áo phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Ao.options.size(); ++i) {
                             if (Ao.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Ao.options.add(new Option(153, 0));
                        Ao.options.add(new Option(58, 10));
                        Ao.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp áo thành công !");
                        p.c.removeItemBody((byte) 2);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Ao);
                       break;
             case 4:
                        Item Gang = p.c.get().ItemBody[4];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[4] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang găng");
                            return;
                        }
                        if (p.c.get().ItemBody[4].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Găng phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Gang.options.size(); ++i) {
                             if (Gang.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Gang.options.add(new Option(153, 0));
                        Gang.options.add(new Option(58, 10));
                        Gang.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp găng thành công !");
                        p.c.removeItemBody((byte) 4);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Gang);
                       break;
                       case 5:
                        Item Nhan = p.c.get().ItemBody[5];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[5] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang nhẫn");
                            return;
                        }
                        if (p.c.get().ItemBody[5].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Nhẫn phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Nhan.options.size(); ++i) {
                             if (Nhan.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Nhan.options.add(new Option(153, 0));
                        Nhan.options.add(new Option(58, 10));
                        Nhan.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp nhẫn thành công !");
                        p.c.removeItemBody((byte) 5);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Nhan);
                       break;
                       case 6:
                        Item Quan = p.c.get().ItemBody[6];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[6] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang quần");
                            return;
                        }
                        if (p.c.get().ItemBody[6].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Quần phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Quan.options.size(); ++i) {
                             if (Quan.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Quan.options.add(new Option(153, 0));
                        Quan.options.add(new Option(58, 10));
                        Quan.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp quânc thành công !");
                        p.c.removeItemBody((byte) 6);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Quan);
                       break;
                       case 7:
                        Item Boi = p.c.get().ItemBody[7];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[7] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang bội");
                            return;
                        }
                        if (p.c.get().ItemBody[7].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Bội phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Boi.options.size(); ++i) {
                             if (Boi.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Boi.options.add(new Option(153, 0));
                        Boi.options.add(new Option(58, 10));
                        Boi.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp bội thành công !");
                        p.c.removeItemBody((byte) 7);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Boi);
                       break;
                       case 8:
                        Item Giay = p.c.get().ItemBody[8];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[8] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang giày");
                            return;
                        }
                        if (p.c.get().ItemBody[8].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Giày phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Giay.options.size(); ++i) {
                             if (Giay.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Giay.options.add(new Option(153, 0));
                        Giay.options.add(new Option(58, 10));
                        Giay.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp giày thành công !");
                        p.c.removeItemBody((byte) 8);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Giay);
                       break;
                       case 9:
                        Item Bua = p.c.get().ItemBody[9];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[9] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang bùa");
                            return;
                        }
                        if (p.c.get().ItemBody[9].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)62, "Bùa phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Bua.options.size(); ++i) {
                             if (Bua.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)62, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 20) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 20 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Bua.options.add(new Option(153, 0));
                        Bua.options.add(new Option(58, 10));
                        Bua.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp bùa thành công !");
                        p.c.removeItemBody((byte) 9);
                        p.c.removeItemBags(980,20);
                        p.c.removeItemBags(981,20);
                        p.c.removeItemBags(982,20);
                        p.c.removeItemBags(984,20);
                        p.c.removeItemBags(985,20);
                        p.c.addItemBag(true, Bua);
                       break;
                        }
            
                break;
             }
          }        
        }
 public static void npctest(Player p, byte npcid, byte menuId, byte b3) throws InterruptedException {
        switch (menuId) {
            case 0: {
                if (p.luong >= 0) {
                    p.updateExp(Level.getMaxExp(130));
//                    LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 250000, " Nhận test", +50000);
                    p.upluongMessage(34450000);
                    p.c.upyenMessage(2000000000);
                    p.c.upxuMessage(1000000000);
                    Item itemup = ItemTemplate.itemDefault(998);
//                    Service.addItemToBagNinja(p.c, ItemTemplate.itemDefault (999), true, true, 5, false, -1);
//                    Service.addItemToBagNinja(p.c, ItemTemplate.itemDefault (979), true, true, 5, false, -1);
                    Service.addItemToBagNinja(p.c, ItemName.VP_VIP, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.PHB_NEW, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.AN_TOC, true, true, 1, false, -1);
                   itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.CANH_THIEN_THAN, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.MAT_16, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.HAKAIRO_YOROI, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.GAY_TRAI_TIM, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.SHIRAIJI, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.GAY_MAT_TRANG, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.HAJIRO, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.MAT_NA_SHIN_AH, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.MAT_NA_VO_DIEN, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.MAT_NA_ONI, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.MAT_NA_KUMA, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.MAT_NA_INU, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.PET_BONG_MA, true, true, 1, false, -1);
                    
                    Service.addItemToBagNinja(p.c, ItemName.PET_YEU_TINH, true, true, 1, false, -1);
                    itemup.upgrade = 16;
                    Service.addItemToBagNinja(p.c, ItemName.GAY_NHU_Y, true, true, 1, false, -1);
//                  
itemup.upgrade = 16;
//                    Service.addItemToBagNinja(p.c, ItemName.LINH_CHI_VAN_NAM, true, true, 5, false, -1);
//                    Service.addItemToBagNinja(p.c, ItemName.LINH_CHI_VAN_NAM, true, true, 5, false, -1);
//                    Service.addItemToBagNinja(p.c, ItemName.LINH_CHI_VAN_NAM, true, true, 5, false, -1);
//                    Service.addItemToBagNinja(p.c, ItemName.LINH_CHI_VAN_NAM, true, true, 5, false, -1);
//                    Service.addItemToBagNinja(p.c, ItemName.LINH_CHI_VAN_NAM, true, true, 5, false, -1);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                }
//                    Service.chatNPC(p, (short) npcid, "Nhận level test thành công , chúc ae bú cu vui vẻ");
                }
                break;
            }
    }
        //thêm npc mới
        public static void npcnew(Player p, byte npcid, byte menuId, byte b3) throws IOException {
      switch(menuId){
          case 0: {
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60 Mới Có Thể Nhận Ngọc Lưu Ly.");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 10000000) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 10m lượng");
                    return;
                }
                Item it = ItemTemplate.itemDefault(1009);
                            it.setLock(true);
                            p.c.addItemBag(true, it);
                            p.upluongMessage(-10000000L);
                            return;
                }
            case 1: {
                Item Item = p.c.get().ItemBody[28];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Ngọc Lưu Ly Lên Người Mới Có Thể Nâng");
                    return;
                }
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60.");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 500000) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 500k lượng");
                    return;
                }

                Item it = ItemTemplate.itemDefault(1009);
                int a = Util.nextInt(1, 8);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < UpgradePet1.Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(UpgradePet1.Options[indexOption], (UpgradePet1.param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 28);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 500000, " Luyện Ngọc Lưu Ly ", -500000);
                p.upluongMessage(-500000);
                String b = "";
                if (a <= 6 && a >= 8) {
                    b = "Ngon ! Thằng Ngu như m cũng luyện được hay thật";
                } else if (a >= 2 && a <= 5) {
                    b = "Chỉ số cũng ổn với nhân phẩm của m !";
                } else {
                    b = "Nhân phẩm như súc dâu bưởi cũng luyện ?????? !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
              case 2:
                Item Item = p.c.get().ItemBody[28];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Ngọc Lưu Ly Lên Người Mới Có Thể Nâng Ngọc Lưu Ly");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.get().ItemBody[28] != null && p.c.get().ItemBody[28].id != ItemName.NGOC_LUU_LY) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có Ngọc Lưu Ly mới có thể nâng cấp");
                    return;
                }
                if (Item.getUpgrade() >= 16) {
                    Service.chatNPC(p, (short) npcid, "Ngọc Lưu Ly đã đạt cấp tối đa");
                    return;
                }
                if (p.luong < 500000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp Ngọc Lưu Ly");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[28].id);
                Service.startYesNoDlg(p, (byte) 11, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + " Lượng  vs " + " Với Tỉ Lệ Thành Công : "
                        + UpgradePet1.Percent[Item.upgrade] + "% không?"
                );
                break;
               case 3: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện Ngọc Lưu Ly cần mang lên người và + 500k Lượng  \n"
                                + "- Muốn Nhận ngọc thì nôn 10m lượng ra ngu ạ \n"
                                + "- Gia nang tu 100k -1m lg \n"
                        + "- Muốn Nhận ngọc thì nôn 10m lượng ra ngu ạ \n"
                        + "- Luyện Ngọc Lưu Ly sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp Ngọc Lưu Ly. Các dòng chỉ số Ngọc Lưu Ly của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất ngân Lượng \n"
                );
                break;
            }
        }
    }
        public static void npccaychay(Player p, byte npcid, byte menuId, byte b3) throws IOException {
      switch(menuId){
          case 0: {
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60 Mới Có Thể Nhận Tờ 500k.");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 10000) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 10k lượng");
                    return;
                }
                Item it = ItemTemplate.itemDefault(1004);
                            it.setLock(true);
                            p.c.addItemBag(true, it);
                            p.upluongMessage(-10000);
                            return;
                }
            case 1: {
                if (p.c.get().nclass == 0) {
                    Service.chatNPC(p, (short) npcid, "Hãy Nhập Học Để Có Thể Lấy Tờ 500k.");
                    return;
                }
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60.");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 5000) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 5k lượng");
                    return;
                }
                 if (p.c.get().ItemBody[27] != null && p.c.get().ItemBody[27].id != ItemName.TO_500k) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có Tờ 500k mới có thể nâng cấp");
                    return;
                }

                Item it = ItemTemplate.itemDefault(1004);
                int a = Util.nextInt(1, 8);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < UpgradeKuma.Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(UpgradeKuma.Options[indexOption], (UpgradeKuma.param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 27);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 5000, " Luyện Tờ 500k ", -5000);
                p.upluongMessage(-5000);
                String b = "";
                if (a <= 6 && a >= 8) {
                    b = "Ngon ! Thằng Ngu như m cũng luyện được hay thật";
                } else if (a >= 2 && a <= 5) {
                    b = "Chỉ số cũng ổn với nhân phẩm của m !";
                } else {
                    b = "Nhân phẩm như súc dâu bưởi cũng luyện ?????? !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
              case 2:
                Item Item = p.c.get().ItemBody[27];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Tờ 500kLên Người Mới Có Thể Nâng Tờ 500k");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.get().ItemBody[27] != null && p.c.get().ItemBody[27].id != ItemName.TO_500k) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có Ngọc Lưu Ly mới có thể nâng cấp");
                    return;
                }
                if (Item.getUpgrade() >= 16) {
                    Service.chatNPC(p, (short) npcid, "Tờ 500k đã đạt cấp tối đa");
                    return;
                }
                if (p.luong < 5000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp Tờ 500k");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[27].id);
                Service.startYesNoDlg(p, (byte) 13, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + " Lượng  vs " + " Với Tỉ Lệ Thành Công : "
                        + UpgradeKuma.Percent[Item.upgrade] + "% không?"
                );
                break;
               case 3: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện Tờ 500k cần mang lên người và + 5k Lượng  \n"
                        + "- Muốn Nhận thì nôn 10k lượng ra ngu ạ \n"
                        + "- Luyện Tờ 500k sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp Tờ 500k. Các dòng chỉ số Tờ 500k của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất ngân Lượng \n"
                );
                break;
            }
        }
    }
        public static void npcNangCap(Player p, byte npcid, byte menuId, byte b3) throws IOException {
      switch(menuId){
            case 0: {
                switch (b3) {
                case 0: {
                if (p.c.get().nclass == 0) {
                    Service.chatNPC(p, (short) npcid, "Hãy Nhập Học Để Có Thể Luyện Bí Kíp.");
                    return;
                }
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60 Mới Có Thể Make Bí Kíp.");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 5000) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 5000 lượng");
                    return;
                }
                if (p.c.quantityItemyTotal(682) < 10 ) {
                                Service.chatNPC(p, (short) npcid,"Bạn không đủ đá tái tạo.");
                                return;
                            }
                Item it = ItemTemplate.itemDefault(396 + p.c.nclass);
                            it.setLock(true);
                            p.c.addItemBag(true, it);
                            p.c.removeItemBags(682, 10);
                            p.upluongMessage(-5000L);
                            return;
                }
            case 1: {
                if (p.c.ItemBody[15] == null) {
                    Service.chatNPC(p, (short) npcid,"Bạn phải đeo bí kiếp mới có thể xóa được nhé");
                    return;
                }
                if (p.luong < 500) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 500 lượng");
                    return;
                }    
                p.c.removeItemBody((byte) 15);
                p.upluongMessage(-500L);
                return;
               }
                case 2: {
                Item Item = p.c.ItemBody[15];
                if (p.c.get().nclass == 0) {
                    Service.chatNPC(p, (short) npcid, "Hãy Nhập Học Để Có Thể Luyện Bí Kíp.");
                    return;
                }
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60 Mới Có Thể Luyện Bí Kíp.");
                    return;
                }
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Bí Kíp Lên Người Mới Có Thể Luyện Bí Kíp");
                    return;
                }
                if (Item.upgrade >= 1) {
                    Service.chatNPC(p, (short) npcid, "Bí Kíp Đã Được Nâng Cấp Không Thể Luyện Bí Kíp");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 1000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ 1000 Lượng Để Luyện Bí Kíp");
                    return;
                }
                Item it = ItemTemplate.itemDefault(396 + p.c.nclass);
                int a = Util.nextInt(5, 8);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < BiKip.Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(BiKip.Options[indexOption], (BiKip.param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 15);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 1000, " Luyện Bí Kíp ", -1000);
                p.upluongMessage(-1000);
                String b = "";
                if (a <= 6 && a >= 8) {
                    b = "Ngon ! Hi sinh vì Đam Mê thì chưa bao giờ là Ngu";
                } else if (a >= 2 && a <= 5) {
                    b = "Chỉ số MẠNH hay YẾU là do Nhân Phẩm của bạn !";
                } else {
                    b = "Số con đen như mặt con vậy !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
                case 3:{
                Item Item = p.c.get().ItemBody[15];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Bí Kíp Lên Người Mới Có Thể Nâng Bí Kíp");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (Item.getUpgrade() >= 16) {
                    Service.chatNPC(p, (short) npcid, "Bí kíp đã đạt cấp tối đa");
                    return;
                }
                
                if (p.c.quantityItemyTotal(837) < 5 * Item.upgrade) {
                    ItemTemplate data = ItemTemplate.ItemTemplateId(837);
                    Service.chatNPC(p, (short) npcid, "Bạn không đủ " + 5 * Item.upgrade + " viên " + data.name + " để nâng cấp");
                    return;
                }
                if (p.luong < 1000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp Bí Kíp");
                    return;
                }
                if (p.c.xu < 10000000) {
                    Service.chatNPC(p, (short) npcid, "Đéo có xu cũng bày đặt nâng :)).");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[15].id);
                Service.startYesNoDlg(p, (byte) 15, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + 1000 + " Lượng Và " + 5 * Item.upgrade + " Đá Nâng Cấp Với Tỉ Lệ Thành Công : "
                        + BiKip.Percent[Item.upgrade] + "% không?"
                );
                break;
//                Ư
                }
                }
            }
              case 1: { //Luyện Pet
                  switch (b3) {
                case 0:{
                Item Item = p.c.ItemBody[10];
                if (p.c.get().level < 50) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 50 Mới Có Thể Luyện pet.");
                    return;
                }
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo pet Ưng Long Lên Người Mới Có Thể Luyện Pet Ưng long");
                    return;
                }
                if (p.c.get().ItemBody[10] != null && p.c.get().ItemBody[10].id != ItemName.UNG_LONG) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có pet ưng long mới có thể luyện");
                    return;
                }
                if (Item.upgrade >= 1) {
                    Service.chatNPC(p, (short) npcid, "pet ưng long Đã Được Nâng Cấp Không Thể Luyện pet");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 1000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ 1000 Lượng Để Luyện pet");
                    return;
                }
                Item it = ItemTemplate.itemDefault(832);
                int a = Util.nextInt(5, 8);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < UpgradePet.Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(UpgradePet.Options[indexOption], (UpgradePet.param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 10);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 1000, " Luyện pet ", -1000);
                p.upluongMessage(-1000);
                String b = "";
                if (a <= 6 && a >= 8) {
                    b = "Ngon ! Hi sinh vì Đam Mê thì chưa bao giờ là Ngu";
                } else if (a >= 2 && a <= 5) {
                    b = "Chỉ số MẠNH hay YẾU là do Nhân Phẩm của bạn !";
                } else {
                    b = "Nhân phẩm như cc cũng muốn cs ngon !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
              case 1:
                Item Item = p.c.get().ItemBody[10];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Pet Ưng Long Lên Người Mới Có Thể Nâng pet");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.get().ItemBody[10] != null && p.c.get().ItemBody[10].id != ItemName.UNG_LONG) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có pet ưng long mới có thể nâng cấp");
                    return;
                }
                if (Item.getUpgrade() >= 16) {
                    Service.chatNPC(p, (short) npcid, "Pet ưng long đã đạt cấp tối đa");
                    return;
                }
                if (p.luong < 5000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp pet ưng long");
                    return;
                }
                if (p.c.xu < xu[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ xu Để Nâng Cấp pet ưng long");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[10].id);
                Service.startYesNoDlg(p, (byte) 10, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + 5000 + " Lượng  vs " + xu[Item.upgrade] + " xu Với Tỉ Lệ Thành Công : "
                        + UpgradePet.Percent[Item.upgrade] + "% không?"
                );
                break;
//                return;
//            }
              }
              }
                 case 2: {// NTGT
                Item Item = p.c.ItemBody[13];
                if (p.c.get().nclass == 0) {
                    Service.chatNPC(p, (short) npcid, "Hãy Nhập Học Để Có Thể Luyện NTGT.");
                    return;
                }
                if (p.c.get().level < 10) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60 Mới Có Thể Luyện NTGT.");
                    return;
                }
                if (Item == null|| Item.id != 427) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo NTGT Lên Người Mới Có Thể Luyện NTGT");
                    return;
                }
                if (Item.upgrade >= 1) {
                    Service.chatNPC(p, (short) npcid, "NTGT Đã Được Nâng Cấp Không Thể Luyện NTGT");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.c.xu < 10000000) {
                    Service.chatNPC(p, (short) npcid, "K cos xu.");
                    return;
                }
                if (p.luong < 10000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ 10000 Lượng Để Luyện NTGT");
                    return;
                }
                Item it = ItemTemplate.itemDefault(NHAN_THUAT_GIA_TOC_CAP_5);
                int a = Util.nextInt(5, 8);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < UpgradeNhanThuatGiaToc.Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(UpgradeNhanThuatGiaToc.Options[indexOption], (UpgradeNhanThuatGiaToc.param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 13);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 1000, " Luyện Nhẫn Thuật Gia Tộc ", -1000);
              //  LichSu.LichSuXu(p.c.name, p.c.xu, p.c.xu - 1000, " Luyện Nhẫn Thuật Gia Tộc ", -1000);
                p.upluongMessage(-1000);
                String b = "";
                if (a <= 6 && a >= 8) {
                    b = "Ngon ! Hi sinh vì Đam Mê thì chưa bao giờ là Ngu";
                } else if (a >= 2 && a <= 5) {
                    b = "Chỉ số MẠNH hay YẾU là do Nhân Phẩm của bạn !";
                } else {
                    b = "Tại sao m đen như chó vậy !";
                }
                Service.chatNPC(p, (short) npcid, b);
//                return;
                 }
                 break;
//            }
                 case 3: {
                 if (p.c.isNhanban) {
                                Service.chatNPC(p, (short) npcid, "Phân thân không thể sử dụng chức năng này.");
                                return;
                            }     
                 if (p.c.quantityItemyTotal(986 ) < 1) {
                                p.conn.sendMessageLog("Bạn không đủ 1 Vé Vĩ thú ");
                                return;
                                 }
                            if (p.luong < 10000) {
                                    Service.chatNPC(p, (short) npcid, "Hành trang của con cần có 5.000 Lượng để khai mở vĩ thú");
                                    return;
                            }
                            if (p.c.level < 30) {
                                Service.chatNPC(p, (short) npcid, "Con cần đạt trình độ 30 để khai mở vĩ thú hãy chăm chỉ lên nha con");
                                     return;
                            }
                                if (p.c.getBagNull() < 1) {
                                  Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chỗ Trống");
                                  return;
                            }
                                
                                
                                 

                                Item item = ItemTemplate.itemDefault(847, true);
                                item.quantity = 1;
                                p.upluongMessage(-5000L);
                                item.setUpgrade(9);
                                item.setLock(true);
                                Option op = new Option(87, 10000);
                                item.options.add(op);
                                op = new Option(58, 20);
                                item.options.add(op);
                                 op = new Option(67, 20);
                                item.options.add(op);
                                p.c.addItemBag(false, item);
                                p.c.removeItemBags(986, 1);
                 }
                                break;
//                          }
                 case 4: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (b3) {
                    case 0:
                        byte i;
                        Item VuKhi = p.c.ItemBody[1];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[1] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang vũ khí");
                            return;
                        }
                        if (p.c.get().ItemBody[1].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Vũ khí phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < VuKhi.options.size(); ++i) {
                             if (VuKhi.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        VuKhi.options.add(new Option(153, 0));
                        VuKhi.options.add(new Option(58, 15));
                        VuKhi.options.add(new Option(57, 15));
                        p.sendAddchatYellow("Dung hợp vũ khí thành công !");
                        p.c.removeItemBody((byte) 1);
                         p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, VuKhi);
                       break;
                case 1:
                        Item Non = p.c.get().ItemBody[0];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[0] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang nón");
                            return;
                        }
                        if (p.c.get().ItemBody[0].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Nón phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Non.options.size(); ++i) {
                             if (Non.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Non.options.add(new Option(153, 0));
                        Non.options.add(new Option(58, 10));
                        Non.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp nón thành công !");
                        p.c.removeItemBody((byte) 0);
                          p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Non);
                       break;
                       case 2:
                        Item DayChuyen = p.c.get().ItemBody[3];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[3] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang dây chuyền");
                            return;
                        }
                        if (p.c.get().ItemBody[3].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Dây chuyền phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < DayChuyen.options.size(); ++i) {
                             if (DayChuyen.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        DayChuyen.options.add(new Option(153, 0));
                        DayChuyen.options.add(new Option(58, 10));
                        DayChuyen.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp dây chuyền thành công !");
                        p.c.removeItemBody((byte) 3);
                          p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, DayChuyen);
                       break;
                       case 3:
                        Item Ao = p.c.get().ItemBody[2];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[2] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang áo");
                            return;
                        }
                        if (p.c.get().ItemBody[2].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Áo phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Ao.options.size(); ++i) {
                             if (Ao.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Ao.options.add(new Option(153, 0));
                        Ao.options.add(new Option(58, 10));
                        Ao.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp áo thành công !");
                        p.c.removeItemBody((byte) 2);
                        p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Ao);
                       break;
             case 4:
                        Item Gang = p.c.get().ItemBody[4];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[4] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang găng");
                            return;
                        }
                        if (p.c.get().ItemBody[4].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Găng phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Gang.options.size(); ++i) {
                             if (Gang.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Gang.options.add(new Option(153, 0));
                        Gang.options.add(new Option(58, 10));
                        Gang.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp găng thành công !");
                        p.c.removeItemBody((byte) 4);
                           p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Gang);
                       break;
                       case 5:
                        Item Nhan = p.c.get().ItemBody[5];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[5] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang nhẫn");
                            return;
                        }
                        if (p.c.get().ItemBody[5].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Nhẫn phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Nhan.options.size(); ++i) {
                             if (Nhan.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Nhan.options.add(new Option(153, 0));
                        Nhan.options.add(new Option(58, 10));
                        Nhan.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp nhẫn thành công !");
                        p.c.removeItemBody((byte) 5);
                         p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Nhan);
                       break;
                       case 6:
                        Item Quan = p.c.get().ItemBody[6];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[6] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang quần");
                            return;
                        }
                        if (p.c.get().ItemBody[6].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Quần phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Quan.options.size(); ++i) {
                             if (Quan.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Quan.options.add(new Option(153, 0));
                        Quan.options.add(new Option(58, 10));
                        Quan.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp quânc thành công !");
                        p.c.removeItemBody((byte) 6);
                         p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Quan);
                       break;
                       case 7:
                        Item Boi = p.c.get().ItemBody[7];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[7] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang bội");
                            return;
                        }
                        if (p.c.get().ItemBody[7].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Bội phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Boi.options.size(); ++i) {
                             if (Boi.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Boi.options.add(new Option(153, 0));
                        Boi.options.add(new Option(58, 10));
                        Boi.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp bội thành công !");
                        p.c.removeItemBody((byte) 7);
                           p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Boi);
                       break;
                       case 8:
                        Item Giay = p.c.get().ItemBody[8];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[8] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang giày");
                            return;
                        }
                        if (p.c.get().ItemBody[8].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Giày phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Giay.options.size(); ++i) {
                             if (Giay.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Giay.options.add(new Option(153, 0));
                        Giay.options.add(new Option(58, 10));
                        Giay.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp giày thành công !");
                        p.c.removeItemBody((byte) 8);
                           p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
                        p.c.addItemBag(true, Giay);
                       break;
                       case 9:
                        Item Bua = p.c.get().ItemBody[9];
                        if (p.c.isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.ItemBody[9] == null) {
                            Service.chatNPC(p,(short) npcid, "Chưa mang bùa");
                            return;
                        }
                        if (p.c.get().ItemBody[9].getUpgrade() < 16) {
                            Service.chatNPC(p, (short)64, "Bùa phải được nâng lên +16");
                            return;
                        }
                        for (i = 0; i < Bua.options.size(); ++i) {
                             if (Bua.options.get(i).id == 153) {
                                  Service.chatNPC(p, (short)64, "Trang bị đã đã dung hợp");
                                return; 
                       }
                       
                        if (p.c.quantityItemyTotal(980) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 1"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(981) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 2");   
                            return;
                        }
                        if (p.c.quantityItemyTotal(982) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 3"); 
                            return;
                        }
                        if (p.c.quantityItemyTotal(983) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 4"); 
                            return;              
                        }
                        if (p.c.quantityItemyTotal(984) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 5");
                            return;
                        }
                        if (p.c.quantityItemyTotal(985) < 50) {
                            Service.chatNPC(p, (short) npcid, " Hành trang không đủ 50 thẻ dung hợp 6");
                            return;
                        }
                        }

                        Bua.options.add(new Option(153, 0));
                        Bua.options.add(new Option(58, 10));
                        Bua.options.add(new Option(57, 10));
                        p.sendAddchatYellow("Dung hợp bùa thành công !");
                        p.c.removeItemBody((byte) 9);
                        p.c.removeItemBags(980,50);
                        p.c.removeItemBags(981,50);
                        p.c.removeItemBags(982,50);
                        p.c.removeItemBags(984,50);
                        p.c.removeItemBags(985,50);
//                        p.c.removeItemBags(986,50);
                        p.c.addItemBag(true, Bua);
                       break;
                        }
            
                break;
             }
      }
        }
    public static void npcKanata_LoiDai(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0:
//                if (p.c.level < 59) {
//                    p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 59");
//                    return;
//                }
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.party != null && p.c.party.charID != p.c.id) {
                    p.c.party.removePlayer(p.c.id);
                }
                p.c.dunId = -1;
                p.c.isInDun = false;
                p.c.tileMap.leave(p);
                p.restCave();
                p.changeMap(p.c.mapKanata);
                break;
            case 1:
//                if (p.c.level < 59) {
//                    p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 59");
//                    return;
//                }
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }

                if (p.c.party != null && p.c.party.charID != p.c.id) {
                    Service.chatNPC(p, (short) npcid, "Con không phải nhóm trưởng, không thể đặt cược");
                    return;
                }

                Service.sendInputDialog(p, (short) 3, "Đặt tiền cược (lớn hơn 1000 xu và chia hết cho 50)");
                break;
            case 2:
                Server.manager.sendTB(p, "Hướng dẫn", "- Mời đối thủ vào lôi đài\n\n- Đặt tiền cược (Lớn hơn 1000 xu và chia hết cho 50)\n\n- Khi cả 2 đã đặt tiền cược, và số tiền phải thống nhất bằng nhau thì trận so tài mới có thể bắt đầu.\n\n- Khi đã đặt tiền cược, nhưng thoát, mất kết nối hoặc thua cuộc, thì người chơi còn lại sẽ giành chiến thắng\n\n- Số tiền thắng sẽ nhận được sẽ bị trừ phí 5%\n\n- Nếu hết thời gian mà chưa có ai giành chiến thắng thì cuộc so tài sẽ tính hoà, và mỗi người sẽ nhận lại số tiền của mình với mức phí bị trừ 1%");
                break;
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcBiKip(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 60 Mới Có Thể Nhận Bí Kíp.");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 5000) {
                    Service.chatNPC(p, (short) npcid, "Bạn không có đủ 5000 lượng");
                    return;
                }
                if (p.c.quantityItemyTotal(682) < 10) {
                    Service.chatNPC(p, (short) npcid, "Bạn không đủ đá tái tạo.");
                    return;
                }
                Item it = ItemTemplate.itemDefault(396 + p.c.nclass);
                it.setLock(true);
                it.isExpires = false;
                p.c.addItemBag(true, it);
                p.c.removeItemBags(682, 10);
                p.upluongMessage(-5000L);
                break;
            }
            case 1: {
                if (p.c.ItemBody[15] == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn phải đeo bí kiếp mới có thể xóa được nhé");
                    return;
                }
                if (p.luong < 500) {
                    Service.chatNPC(p, (short) npcid, "Bạn không có đủ 500 lượng");
                    return;
                }
                p.c.removeItemBody((byte) 15);
                p.upluongMessage(-500L);
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcAdmin(Player p, byte npcid, byte menuId, byte b3) throws InterruptedException {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.quatanthu == 0) {
                    p.updateExp(Level.getMaxExp(10));
                    LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 250000, " Nhận Qùa Tân Thủ", +50000);
                    p.upluongMessage(500000);
                    p.c.upyenMessage(1000000000);
                    p.c.upxuMessage(1000000);
                    Service.addItemToBagNinja(p.c, ItemName.LINH_CHI_VAN_NAM, true, true, 5, false, -1);
                    p.c.quatanthu = 1;
                    Service.chatNPC(p, (short) npcid, "Nhận quà thành công. Hãy Đến Trường Để Nhập Học Và Chăm Chỉ Tu Luyện Nhé");
                } else {
                    Service.chatNPC(p, (short) npcid, "Mỗi tài khoản chỉ được nhận 1 lần con nhé!");
                }
                break;
            }
            case 1: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.isDiemDanh == 0) {
                    LichSu.LichSuLuong(p.c.name, p.luong, p.luong + 1000, " Điểm Danh Hằng Ngày", +1000);
                    p.upluongMessage(1000L);
                    p.c.isDiemDanh = 1;
                    Service.chatNPC(p, (short) npcid, "Điểm danh thành công, chúc con chơi game vui vẽ.");
                } else {
                    Service.chatNPC(p, (short) npcid, "Hôm nay con đã điểm danh rồi, hãy quay lại vào ngày hôm sau nha!");
                }
                break;
            }
            case 2: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.level == 1) {
                    p.conn.sendMessageLog("Không thể thực hiện thao tác này..");
                    return;
                }
                if (p.c.get().exptype == 1) {
                    p.c.get().exptype = 0;
                    p.conn.sendMessageLog("Đã Tắt Nhận Exp . Tham Gia Đánh Quái Hoặc Ăn Sự Kiện Sẽ Không Nhận Được EXP.");
                } else {
                    p.c.get().exptype = 1;
                    p.conn.sendMessageLog("Đã Bật Nhận Exp.");
                }
                break;
            }
            case 3: {
                        if (p.luong < 10000){
                        p.conn.sendMessageLog("bạn không đủ 10.000 lượng để đổi");
                        return;
                        }
                        p.upluongMessage(-10000);
                        p.c.pointNon    += 100;
                        p.c.pointVukhi += 100;
                        p.c.pointAo += 100;
                        p.c.pointLien += 100;
                        p.c.pointGangtay += 100;
                        p.c.pointNhan += 100;
                        p.c.pointQuan += 100;
                        p.c.pointNgocboi += 100;
                        p.c.pointGiay += 100;
                        p.c.pointPhu += 100;
                        p.conn.sendMessageLog("Bạn nhận được mỗi loại 100 điểm");
                            return;
                        }
            
            //
            case 4:{
//                }
                if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
                if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
            switch (b3) {
                case 0: {
                    if (p.c.nclass == 1) {
                        p.conn.sendMessageLog("Đang ở phái kiếm chuyển cc");
                        return;
                    }
                    if (p.c.get().ItemBody[1] != null) {
                        p.conn.sendMessageLog("Phải tháo vũ khí trước khi chuyển phái");
                        return;
                    }
                    p.c.nclass = 1;
                    p.restSpoint();
                    p.restPpoint();
                    p.loadSkill();
                    p.c.upxuMessage(-1000000000);
                    Service.chatNPC(p, (short) npcid, "Chuyển phái thành công. Tự động thoát sau 5 giây");
                    int TimeSeconds =5 ;
                    while (TimeSeconds > 0) {
                            TimeSeconds--;
                            Thread.sleep(1000);
                            }
                            Client.gI().kickSession(p.conn);
                            return;
                            
                }
                case 1: {
                          if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
                    if (p.c.nclass == 2) {
                        p.conn.sendMessageLog("Đang ở phái tiêu chuyển cc");
                        return;
                    }
                    if (p.c.get().ItemBody[1] != null) {
                        p.conn.sendMessageLog("Phải tháo vũ khí trước khi chuyển phái");
                        return;
                    }
                    p.c.nclass = 2;
                    p.restSpoint();
                    p.restPpoint();
                    p.loadSkill();
                    p.upluongMessage(-500000L);
                    p.c.upxuMessage(-1000000000);
                    Service.chatNPC(p, (short) npcid, "Chuyển phái thành công. Tự động thoát sau 5 giây");
                    int TimeSeconds =5 ;
                    while (TimeSeconds > 0) {
                            TimeSeconds--;
                            Thread.sleep(1000);
                            }
                            Client.gI().kickSession(p.conn);
                            return;
                }
                case 2: {
if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
                    if (p.c.nclass == 3) {
                        p.conn.sendMessageLog("Đang ở phái kunai chuyển cc");
                        return;
                    }
                    if (p.c.get().ItemBody[1] != null) {
                        p.conn.sendMessageLog("Phải tháo vũ khí trước khi chuyển phái");
                        return;
                    }
                    p.c.nclass = 3;
                    p.restSpoint();
                    p.restPpoint();
                    p.loadSkill();
                    p.upluongMessage(-500000L);
                    p.c.upxuMessage(-1000000000);
                    Service.chatNPC(p, (short) npcid, "Chuyển phái thành công. Tự động thoát sau 5 giây");
                    int TimeSeconds =5 ;
                    while (TimeSeconds > 0) {
                            TimeSeconds--;
                            Thread.sleep(1000);
                            }
                            Client.gI().kickSession(p.conn);
                            return;
                }
                case 3: {
if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
                    if (p.c.nclass == 4) {
                        p.conn.sendMessageLog("Đang ở phái cung chuyển cc");
                        return;
                    }
                    if (p.c.get().ItemBody[1] != null) {
                        p.conn.sendMessageLog("Phải tháo vũ khí trước khi chuyển phái");
                        return;
                    }
                    p.c.nclass = 4;
                    p.restSpoint();
                    p.restPpoint();
                    p.loadSkill();
                    p.upluongMessage(-500000L);
                    p.c.upxuMessage(-1000000000);
                    Service.chatNPC(p, (short) npcid, "Chuyển phái thành công. Tự động thoát sau 5 giây");
                    int TimeSeconds =5 ;
                    while (TimeSeconds > 0) {
                            TimeSeconds--;
                            Thread.sleep(1000);
                            }
                            Client.gI().kickSession(p.conn);
                            return;
                }
                case 4: {
    if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
                    if (p.c.nclass == 5) {
                        p.conn.sendMessageLog("Đang ở phái đao chuyển cc");
                        return;
                    }
                    if (p.c.get().ItemBody[1] != null) {
                        p.conn.sendMessageLog("Phải tháo vũ khí trước khi chuyển phái");
                        return;
                    }
                    p.c.nclass = 5;
                    p.restSpoint();
                    p.restPpoint();
                    p.loadSkill();
                    p.upluongMessage(-500000L);
                    p.c.upxuMessage(-1000000000);
                    Service.chatNPC(p, (short) npcid, "Chuyển phái thành công. Tự động thoát sau 5 giây");
                    int TimeSeconds =5 ;
                    while (TimeSeconds > 0) {
                            TimeSeconds--;
                            Thread.sleep(1000);
                            }
                            Client.gI().kickSession(p.conn);
                            return;
                }    
                case 5: {
                    if (p.luong <= 500000) {
                Service.chatNPC(p, (short) npcid, "Bạn không đủ 500k lượng");
                return;
            }
            if (p.c.xu <= 1000000000) {
                Service.chatNPC(p, (short) npcid, "Nôn 1b xu đây");
                return;
            }
                    if (p.c.nclass == 6) {
                        p.conn.sendMessageLog("Đang ở phái quạt chuyển cc");
                        return;
                    }
                    if (p.c.get().ItemBody[1] != null) {
                        p.conn.sendMessageLog("Phải tháo vũ khí trước khi chuyển phái");
                        return;
                    }
                    p.c.nclass = 6;
                    p.restSpoint();
                    p.restPpoint();
                    p.loadSkill();
                    p.upluongMessage(-500000L);
                    p.c.upxuMessage(-1000000000);
                    Service.chatNPC(p, (short) npcid, "Chuyển phái thành công. Tự động thoát sau 5 giây");
                    int TimeSeconds =5 ;
                    while (TimeSeconds > 0) {
                            TimeSeconds--;
                            Thread.sleep(1000);
                            }
                            Client.gI().kickSession(p.conn);
                            return;
                }
            }
            }
            break;
            case 5:
                Server.manager.sendTB(p, "Bảng Giá",
                        "- Bảng Giá Mua Item Vip, Gói Vip Server .\n"
                        + "- Obito 100k .\n"
                        + "- Cánh all 150k .\n"
                        + "- PHB NEW 50k .\n"
                        + "- Mn new tuỳ chọn all giá 100/1 .\n"
//                        + "- Vip5 = 250k .\n"
                        + "- I. Trang Bị 1 .\n"
                        + "- Hỏa Long +16 = 50k .\n"
                        + "- Ứng Long +16 = 100k .\n"
                        + "- Vĩ Thú Siêu Cấp +16 = 100k .\n"
                        + "- Mặt Nạ Super/Onna +16 = 50k .\n"
                        + "- Mặt Nạ Thỏ , Sát thủ, Hannya +16 = 120k .\n"
//                        + "- Mặt Nạ Hannya +16 = 120k .\n"
//                        + "- Mặt Nạ Tho +16 = 100k .\n"
                        + "- Yoroi +16 = 50k .\n"
                        + "- Vật Phẩm VIP +16 = 150k .\n"
                        + "- Hakai Yoroi +16 = 100k .\n"
                        + "- Mắt Sukaigan +16 = 100k .\n"
                        + "- Bí Kíp +16 = 50k .\n"
                        + "- Huyễn Thuật +16 = 150k .\n"
                        + "- Taijutsu +16 = 150k .\n"
                        + "- Sói/ Xe/ Siêu Xe 5sao = 70k .\n"
                        + "- Hỏa Kỳ Lân 5sao = 150k .\n"
                        + "- Set trang bị thú tl9 = 70k .\n"
                        + "- Set Trang bị 9x +16 = 50k .\n"
                        + "- Set Trang bị 9x +16 tl9 = 200k .\n"
                        + "- Set Trang bị 10x +16 tl9 = 350k .\n"
                        + "- Set NaruTo +16 tl9 = 300k .\n"
                        + "- Set Trang bị 12x +16 tl9 = 500k .\n"
                        + "- Set Trang bị 125 +16 tl9 = 750k .\n"
                        + "- Set Trang bị 125 +16 tl9 = 750k .\n"
                        + "- Ngọc Khảm +10 = 15k/1 viên .\n"
                        + "- II: Trang Bị 2 .\n"
                        + "- Ngọc Bội Thần +16 = 150k .\n"  
                        + "- Thú Hồn +16 = 100k .\n"
                        + "- Nhật Tử Lam Phong +16 = 50k .\n"
                        + "- Thiên Nguyệt Chi Nữ +16 = 50k .\n"
                        + "- Gậy Mặt Trang, Trái Tim +16 = 50k .\n"
                        + "- Gậy Như Ý +16 = 100k .\n"
                        + "- Mặt Nạ Hổ +16 = 70k .\n"
                        + "- Mặt Nạ Vô Diện +16 = 70k .\n"
                        + "- Mặt Nạ Oni/Inu/Kuma +16 =50k .\n"
                        + "- Mặt Nạ ShinAh +16 = 50k .\n"
                        + "- Pet Yêu Tinh +16 = 50k .\n"
                        + "- Pet Bóng Ma +16 = 50k .\n"
                        + "- Shiraji/Hajiro +16 = 100k .\n"
                        + "- Áo Dài Nam/Nữ +16 = 100k .\n"
                        + "- Ấn Tộc +16 = 100k .\n"
                        + "- Thien son tuyet lien +16 = 250k .\n"
                        + "- Samurai Nam/Nữ +16 = 70k .\n"
                        + "- Vũ Khí Thuần Vương +16 = 150k .\n"
                        + "- III: Cải Trang Hào Quang .\n"
                        + "- Santa Claus +10 = 50k .\n"
                        + "- hoàng quang nộ xanh = 50k .\n"
                        + "- hoàng quang nộ tím = 50k .\n"
                        + "- hoàng quang nộ vàng = 50k .\n"
                        + "- Danh Hiệu Tự Chọn = 100k .\n"
                        + "- Yukimura +10 = 50k .\n"
                        + "- Sumimura +10 = 50k .\n"
                        + "- 10k = 50m xu .\n"
                        + "- 10k = 50k lượng .\n"
                        + "- Inbox Zalo Admin[Công] .\n"
                );
                break;
            case 6: {
                    if (p.c.level < 130) {
                        p.conn.sendMessageLog("yêu cầu level trên 130 chưa có mở đâu cha");
                        return;
                    }
//                    if (p.c.chuyensinh >= 3) {
//                        p.conn.sendMessageLog("Het Luot");
//                        return;
//                    }
                    if (p.luong < 1000000) {
                        p.conn.sendMessageLog("bạn không có đủ 1tr lượng");
                        return;
                    }
                    if (p.c.xu < 1500000000) {
                        p.conn.sendMessageLog("bạn không có đủ 1ty5 xu");
                        return;
                    }
                    if (Util.percent(100, 1)) {
                        p.updateExp(Level.getMaxExp(10) - p.c.exp);
//                        p.c.chuyensinh += 1;
                        Manager.chatKTG("Kinh thiên động địa: " + p.c.name + " đã chuyển sinh thành công tiến thêm 1 bước trên con đường thành thần");
                        p.upluongMessage(-1000000);
                        p.c.upxuMessage(-1500000000);
                        break;
                    }
                    
                    Manager.chatKTG("Người chơi: " + p.c.name + " đã chuyển sinh thất bại.");
                    p.upluongMessage(-1000000);
                    p.c.upxuMessage(-1000000000);
                    break;
                }
            default: {
                Service.chatNPC(p, (short) npcid, "NsoKey.top!");
                break;
            }
        }
    }
public static void MapBoss(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
//               if (p.c.isVIP >= 5) {
//                   p.sendAddchatYellow("Chỉ mở trên live");
//                            Map ma = Manager.getMapid(201);
//                            for (TileMap area : ma.area) {
//                                if (area.numplayers < ma.template.maxplayers) {
//                                    p.c.tileMap.leave(p);
//                                    area.EnterMap0(p.c);
//                                    return;
//                                }
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[201];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
//                    }
//                            }
//                        } else {
//                            p.conn.sendMessageLog("Chỉ mở trên live");
//                        }
            }
        }
}
                  
    public static void cs(Player p, byte npcid, byte menuId, byte b3) {
         switch (menuId) {
     case 0: {
                    if (p.c.level < 130) {
                        p.conn.sendMessageLog("yêu cầu level trên 130 chưa có mở đâu cha");
                        return;
                    }
                    if (p.c.vip != 1) {
                        p.conn.sendMessageLog("yêu cầu xem liveeeee");
                        return;
                    }
                    if (p.luong < 200000) {
                        p.conn.sendMessageLog("bạn không có đủ 200k lượng");
                        return;
                    }
                    if (p.c.xu < 200000001) {
                        p.conn.sendMessageLog("bạn không có đủ 200m xu");
                        return;
                    }
//                    if (p.c.isVIP == 3 || p.c.isVIP == 2) {
//                        p.conn.sendMessageLog("bạn không duoc chuyen sinh");
//                        return;
//                    }
                    if (Util.percent(100, 5)) {
                        p.updateExp(Level.getMaxExp(100) - p.c.exp);
                        Manager.chatKTG("Biết Tin Gì chưa: " + p.c.name + " đã chuyển sinh thành công tiến thêm 1 bước trên con đường thành nghẹoooo");
                        p.upluongMessage(-200000);
                        p.c.upxuMessage(-200000000);
                        break;
                    }
                    
                    Manager.chatKTG("Người nghẹoooo: " + p.c.name + " đã Isekai thất bại.");
                    p.upluongMessage(-200000);
                    p.c.upxuMessage(-200000000);
                    break;
                }
    }
    }
    public static void npcRikudou_ChienTruong(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                p.c.typepk = 0;
                Service.ChangTypePkId(p.c, (byte) 0);
                p.c.tileMap.leave(p);
                p.restCave();
                p.changeMap(p.c.mapLTD);
                break;
            }
            case 1: {
                Service.evaluateCT(p.c);
                break;
            }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
        }
    }

    public static void npcKagai_GTC(Player p, byte npcid, byte menuId, byte b3) {
        switch (p.c.mapid) {
            case 117: {
                switch (menuId) {
                    case 0: {
                        p.c.typepk = 0;
                        Service.ChangTypePkId(p.c, (byte) 0);
                        p.c.tileMap.leave(p);
                        p.restCave();
                        p.changeMap(p.c.mapLTD);
                        break;
                    }
                    case 1: {
                        Service.chatNPC(p, (short) npcid, "Đặt cược");
                        Service.sendInputDialog(p, (short) 8, "Đặt tiền cược (Bội số của 1000)");
                        break;
                    }

                    default: {
                        Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                        break;
                    }
                }
                break;
            }
            case 118:
            case 119: {
                switch (menuId) {
                    case 0: {
                        p.c.typepk = 0;
                        Service.ChangTypePkId(p.c, (byte) 0);
                        p.c.tileMap.leave(p);
                        p.restCave();
                        p.changeMap(p.c.mapLTD);
                        break;
                    }
                    case 1: {
                        Server.manager.sendTB(p, "Kết quả", "- Gia tộc " + p.c.tileMap.map.giaTocChien.clan1.name + " giành được " + p.c.tileMap.map.giaTocChien.pointClan1 + " điểm.\n"
                                + "- Gia tộc " + p.c.tileMap.map.giaTocChien.clan2.name + " giành được " + p.c.tileMap.map.giaTocChien.pointClan2 + " điểm.\n"
                                + "Điểm của bạn " + p.c.pointGTC);
                        break;
                    }
                    default: {
                        Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                        break;
                    }
                }
                break;
            }
        }
    }
    public static void npcVuaHung(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                if (p.luong < 500000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 500k lượng mới có thể đổi");
                    break;
                }
                if (p.c.quantityItemyTotal(662) < 20000) {
                    p.conn.sendMessageLog("Con phải mang 20k lệnh bài năng động đến cho ta");
                    break;
                }
//                if (p.c.quantityItemyTotal(1003) < 10000) {
//                    p.conn.sendMessageLog("Con phải mang 10k nước dưa hấu đến cho ta");
//                    break;
//                }
                p.c.removeItemBags(662, 20000);
//                p.c.removeItemBags(1003, 10000);
                p.upluongMessage(-500000L);
                p.sendAddchatYellow("Bạn nhận được phượng hoàng băng new");
                Item itemup = ItemTemplate.itemDefault(1000);
                p.c.addItemBag(true, itemup);
                break;
            }
            case 1: {
                if (p.luong < 1000000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 1 triệu lượng mới có thể nâng cấp");
                    break;
                }
                if (p.c.quantityItemyTotal(694) < 1) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Con phải mang mắt 10 đến để đổi mắt 16");
                    break;
                }
                if (p.c.quantityItemyTotal(1006) < 10000) {
                    p.conn.sendMessageLog("Bạn phải thu thập đủ 10000 mảnh ghép để triệu hồi quạ bí ẩn");
                    break;
                }
                if (p.c.quantityItemyTotal(694) < 1) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Con phải mang mắt 10 đến để đổi mắt 16");
                    break;
                }
                p.c.removeItemBags(1006, 10000);
                p.c.removeItemBags(694, 1);
                p.upluongMessage(-1000000L);
                p.sendAddchatYellow("Bạn nhận được con mắt mangekyou sharingan của Gia tộc Uchihahaha");
                Item itemup = ItemTemplate.itemDefault(1005);
                itemup.upgrade = 16;
                p.c.addItemBag(true, itemup);
                break;
            }
            case 2: {
                if (p.c.quantityItemyTotal(648) < 5000 || p.c.quantityItemyTotal(649) < 5000 || p.c.quantityItemyTotal(650) < 5000 || p.c.quantityItemyTotal(651) < 5000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần mỗi loại Huy chương chiến công 5000 chiếc");
                    break;
                }
                if (p.luong < 200000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 200k lượng");
                    break;
                }
                Item itemup = ItemTemplate.itemDefault(652);
                p.upluongMessage(-200000L);
                itemup.upgrade = 10;
                p.c.removeItemBags(648, 5000);
                p.c.removeItemBags(649, 5000);
                p.c.removeItemBags(650, 5000);
                p.c.removeItemBags(651, 5000);
                p.c.addItemBag(false, itemup);
                break;
            }
            case 3: {
                if (p.c.quantityItemyTotal(648) < 5000 || p.c.quantityItemyTotal(649) < 5000 || p.c.quantityItemyTotal(650) < 5000 || p.c.quantityItemyTotal(651) < 5000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần mỗi loại Huy chương chiến công 5.000 chiếc");
                    break;
                }
                if (p.luong < 200000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 200k lượng");
                    break;
                }
                Item itemup = ItemTemplate.itemDefault(653);
                p.upluongMessage(-200000L);
                itemup.upgrade = 10;
                p.c.removeItemBags(648, 5000);
                p.c.removeItemBags(649, 5000);
                p.c.removeItemBags(650, 5000);
                p.c.removeItemBags(651, 5000);
                p.c.addItemBag(false, itemup);
                break;
            }
            case 4: {
                if (p.c.quantityItemyTotal(648) < 5000 || p.c.quantityItemyTotal(649) < 5000 || p.c.quantityItemyTotal(650) < 5000 || p.c.quantityItemyTotal(651) < 5000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần mỗi loại Huy chương chiến công 5000 chiếc");
                    break;
                }
                if (p.luong < 200000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 200k lượng");
                    break;
                }
                Item itemup = ItemTemplate.itemDefault(654);
                p.upluongMessage(-200000L);
                itemup.upgrade = 10;
                p.c.removeItemBags(648, 5000);
                p.c.removeItemBags(649, 5000);
                p.c.removeItemBags(650, 5000);
                p.c.removeItemBags(651, 5000);
                p.c.addItemBag(false, itemup);
                break;
            }
            case 5: {
                if (p.c.quantityItemyTotal(648) < 5000 || p.c.quantityItemyTotal(649) < 5000 || p.c.quantityItemyTotal(650) < 5000 || p.c.quantityItemyTotal(651) < 5000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần mỗi loại Huy chương chiến công 5000 chiếc");
                    break;
                }
                if (p.luong < 200000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 200k lượng");
                    break;
                }
                Item itemup = ItemTemplate.itemDefault(655);
                p.upluongMessage(-200000L);
                itemup.upgrade = 10;
                p.c.removeItemBags(648, 5000);
                p.c.removeItemBags(649, 5000);
                p.c.removeItemBags(650, 5000);
                p.c.removeItemBags(651, 5000);
                p.c.addItemBag(false, itemup);
                break;
            }
            case 6: {
//                if (p.c.quantityItemyTotal(851) < 2000 || p.c.quantityItemyTotal(852) < 2000 || p.c.quantityItemyTotal(853) < 2000 || p.c.quantityItemyTotal(854) < 2000) {
//                    Service.chatNPC(p, Short.valueOf(npcid), "Cần mỗi loại châu 2.000 viên");
//                    break;
//                }
                if (p.c.quantityItemyTotal(647) < 500) {
                    p.conn.sendMessageLog("Bạn cần có 500 rương ma quái");
                    break;
                }
                if (p.c.xu < 50000000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 50tr xu");
                    break;
                }
//                Item itemup = ItemTemplate.itemDefault(995);
                p.c.upxuMessage(-50000000);
                Service.addItemToBagNinja(p.c, ItemName.RUONG_TB_12X, true, true, 5, false, -1);
//                p.c.removeItemBags(851, 2000);
//                p.c.removeItemBags(852, 2000);
//                p.c.removeItemBags(853, 2000);
//                p.c.removeItemBags(854, 2000);
                p.c.removeItemBags(647, 500);
//                p.c.addItemBag(false, itemup);
                break;
            }
            case 7: {
//                if (p.c.quantityItemyTotal(851) < 10000 || p.c.quantityItemyTotal(852) < 10000 || p.c.quantityItemyTotal(853) < 10000 || p.c.quantityItemyTotal(854) < 10000) {
//                    Service.chatNPC(p, Short.valueOf(npcid), "Cần mỗi loại châu 10.000 viên");
//                    break;
//                }
                if (p.c.xu < 100000000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 100m xu");
                    break;
                }
                if (p.c.quantityItemyTotal(647) < 1500) {
                    p.conn.sendMessageLog("Bạn cần có 1500 rương ma quái");
                    break;
                }
//                Item itemup = ItemTemplate.itemDefault(996);
Service.addItemToBagNinja(p.c, ItemName.RUONG_VK_12X, true, true, 5, false, -1);
                p.c.upxuMessage(-100000000);
//                p.c.removeItemBags(851, 10000);
//                p.c.removeItemBags(852, 10000);
//                p.c.removeItemBags(853, 10000);
//                p.c.removeItemBags(854, 10000);
                p.c.removeItemBags(647, 1500);
//                p.c.addItemBag(false, true, itemup);
                break;
            }
            case 8: {
                Server.manager.sendTB(p, "Hướng dẫ nè đọc đi", "Bạn phải tích đủ mảnh quạ thông qua việc ăn sự kiện\n>1: Nâng Mắt 11<\n-5000 mảnh quạ bí ẩn\n-1 triệu lượng\n-1 mắt 10\n>2: Đổi ngọc 10<\n>Huy chương chiến công đồng 10k<\n-Huy chương chiến công bạc 10k\n-Huy chương chiến công vàng 10k"
                        + "\n-Huy chương chiến công bạch kim 10k\n-Lượng 500.000\n"
                        + "-Sau khi đổi sẽ nhận được ngọc cấp 10 ( chỉ dùng để luyện )\n>3: rương trang bị<\n>"
                        + "<\n-rương ma quái 500\n-xu 50.000.000\n"
                        + "-Sau khi đổi sẽ nhận được rương trang bị 12x\n>4:"
                        + " rương Vũ Khí<\n>100m xu<\n-\n-rương ma quái 1500\n-\n-Sau khi đổi sẽ nhận được rương vũ khí 12x");
                break;
            }
            default: {
                Service.chatNPC(p, Short.valueOf(npcid), "Chức năng này đang cập nhật!");
            }
        }
    }
    public static void npcdoixe (Player p, int b3, int npcid, int menuId) {
        switch (menuId) {
        //xevip
        case 0: switch (b3) {
            case 0: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 500000) {
                    p.conn.sendMessageLog("Cần 500k lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.upluongMessage(-500000);
                Item itemup = ItemTemplate.itemDefault(524);
                        Option op65 = new Option(66, 500);
                        Option op66 = new Option(73, 2100);
                        Option op1 = new Option(114, 160);
                        Option op2 = new Option(68,160);
                        Option op3 = new Option(67, 100);
                        Option op4 = new Option(11,10);
                        Option op5 = new Option(12, 10);
                        Option op6 = new Option(13,10);
                        Option op7 = new Option(6, 500);
                        Option op8 = new Option(7,500);
                        Option op9 = new Option(102,500);
                        Option op10 = new Option(103, 500);
                        Option op11 = new Option(113,200);
                        Option op12 = new Option(121,10);
                        Option op13 = new Option(94,10);
                        ArrayList<Option> opadd = new ArrayList<>();
                        opadd.add(op65);opadd.add(op66);opadd.add(op1);opadd.add(op2);opadd.add(op3);opadd.add(op4);opadd.add(op5);opadd.add(op6);opadd.add(op7);opadd.add(op8);;opadd.add(op9);;opadd.add(op10);;opadd.add(op11);;opadd.add(op12);;opadd.add(op13);
                         for (long i =0; i<Util.nextInt(5,15);i++) {
                            int j = (int)Util.nextInt(opadd.size());
                            itemup.options.add(opadd.get(j));
                            opadd.remove(j);
                        }
                
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 99;
                itemup.sys = 4;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
//            Sói
            case 1: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
//                if (p.coin < 100000) {
//                    p.conn.sendMessageLog("Cần 100000 coin");
//                    return;
//                }
                if (p.luong < 500000) {
                    p.conn.sendMessageLog("Cần 500000 lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                 p.upluongMessage(-500000);
                Item itemup = ItemTemplate.itemDefault(443);
                        Option op65 = new Option(66, 500);
                        Option op66 = new Option(73, 2100);
                        Option op1 = new Option(114, 160);
                        Option op2 = new Option(68,160);
                        Option op3 = new Option(67, 100);
                        Option op4 = new Option(11,10);
                        Option op5 = new Option(12, 10);
                        Option op6 = new Option(13,10);
                        Option op7 = new Option(6, 500);
                        Option op8 = new Option(7,500);
                        Option op9 = new Option(102,500);
                        Option op10 = new Option(103, 500);
                        Option op11 = new Option(113,200);
                        Option op12 = new Option(121,10);
                        Option op13 = new Option(94,10);
                        ArrayList<Option> opadd = new ArrayList<>();
                        opadd.add(op65);opadd.add(op66);opadd.add(op1);opadd.add(op2);opadd.add(op3);opadd.add(op4);opadd.add(op5);opadd.add(op6);opadd.add(op7);opadd.add(op8);;opadd.add(op9);;opadd.add(op10);;opadd.add(op11);;opadd.add(op12);;opadd.add(op13);
                         for (long i =0; i<Util.nextInt(5,15);i++) {
                            int j = (int)Util.nextInt(opadd.size());
                            itemup.options.add(opadd.get(j));
                            opadd.remove(j);
                        }
                
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 99;
                itemup.sys = 4;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
            case 2: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
//                if (p.coin < 100000) {
//                    p.conn.sendMessageLog("Cần 100000 coin");
//                    return;
//                }
                if (p.luong < 500000) {
                    p.conn.sendMessageLog("Cần 500000 lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
              p.upluongMessage(-500000);
                Item itemup = ItemTemplate.itemDefault(485);
                        Option op65 = new Option(66, 500);
                        Option op66 = new Option(73, 2100);
                        Option op1 = new Option(114, 160);
                        Option op2 = new Option(68,160);
                        Option op3 = new Option(67, 100);
                        Option op4 = new Option(11,10);
                        Option op5 = new Option(12, 10);
                        Option op6 = new Option(13,10);
                        Option op7 = new Option(6, 500);
                        Option op8 = new Option(7,500);
                        Option op9 = new Option(102,500);
                        Option op10 = new Option(103, 500);
                        Option op11 = new Option(113,200);
                        Option op12 = new Option(121,10);
                        Option op13 = new Option(94,10);
                        ArrayList<Option> opadd = new ArrayList<>();
                        opadd.add(op65);opadd.add(op66);opadd.add(op1);opadd.add(op2);opadd.add(op3);opadd.add(op4);opadd.add(op5);opadd.add(op6);opadd.add(op7);opadd.add(op8);;opadd.add(op9);;opadd.add(op10);;opadd.add(op11);;opadd.add(op12);;opadd.add(op13);
                         for (long i =0; i<Util.nextInt(5,15);i++) {
                            int j = (int)Util.nextInt(opadd.size());
                            itemup.options.add(opadd.get(j));
                            opadd.remove(j);
                        }
                
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 99;
                itemup.sys = 4;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
            case 3: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                
                if (p.luong < 500000) {
                    p.conn.sendMessageLog("Cần 500000 lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
           p.upluongMessage(-500000);
                Item itemup = ItemTemplate.itemDefault(776);
                        Option op65 = new Option(66, 500);
                        Option op66 = new Option(73, 2100);
                        Option op1 = new Option(114, 160);
                        Option op2 = new Option(68,160);
                        Option op3 = new Option(67, 100);
                        Option op4 = new Option(11,10);
                        Option op5 = new Option(12, 10);
                        Option op6 = new Option(13,10);
                        Option op7 = new Option(6, 500);
                        Option op8 = new Option(7,500);
                        Option op9 = new Option(102,500);
                        Option op10 = new Option(103, 500);
                        Option op11 = new Option(113,200);
                        Option op12 = new Option(121,10);
                        Option op13 = new Option(94,10);
                        ArrayList<Option> opadd = new ArrayList<>();
                        opadd.add(op65);opadd.add(op66);opadd.add(op1);opadd.add(op2);opadd.add(op3);opadd.add(op4);opadd.add(op5);opadd.add(op6);opadd.add(op7);opadd.add(op8);;opadd.add(op9);;opadd.add(op10);;opadd.add(op11);;opadd.add(op12);;opadd.add(op13);
                         for (long i =0; i<Util.nextInt(5,15);i++) {
                            int j = (int)Util.nextInt(opadd.size());
                            itemup.options.add(opadd.get(j));
                            opadd.remove(j);
                        }
                
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 99;
                itemup.sys = 4;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
            case 4: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
        
                if (p.luong < 500000) {
                    p.conn.sendMessageLog("Cần 500000 lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.upluongMessage(-500000);
                Item itemup = ItemTemplate.itemDefault(777);
                        Option op65 = new Option(66, 500);
                        Option op66 = new Option(73, 2100);
                        Option op1 = new Option(114, 160);
                        Option op2 = new Option(68,160);
                        Option op3 = new Option(67, 100);
                        Option op4 = new Option(11,10);
                        Option op5 = new Option(12, 10);
                        Option op6 = new Option(13,10);
                        Option op7 = new Option(6, 500);
                        Option op8 = new Option(7,500);
                        Option op9 = new Option(102,500);
                        Option op10 = new Option(103, 500);
                        Option op11 = new Option(113,200);
                        Option op12 = new Option(121,10);
                        Option op13 = new Option(94,10);
                        ArrayList<Option> opadd = new ArrayList<>();
                        opadd.add(op65);opadd.add(op66);opadd.add(op1);opadd.add(op2);opadd.add(op3);opadd.add(op4);opadd.add(op5);opadd.add(op6);opadd.add(op7);opadd.add(op8);;opadd.add(op9);;opadd.add(op10);;opadd.add(op11);;opadd.add(op12);;opadd.add(op13);
                         for (long i =0; i<Util.nextInt(5,15);i++) {
                            int j = (int)Util.nextInt(opadd.size());
                            itemup.options.add(opadd.get(j));
                            opadd.remove(j);
                        }
                
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 99;
                itemup.sys = 4;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }

        }
}
    }
    public static void npcshopjarai(Player p, byte npcid, byte menuId, byte b3) throws IOException, InterruptedException, SQLException {
        switch (menuId) { 
      case 0: {
            Server.manager.sendTB(p,"Lượng hiện tại",
                    "\n-lượng hiện tại của bạn là : "+p.luong);
            break;
                    }
            
            case 1: {
                switch (b3) {
                    case 0: {//nón
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k luong.hãy nạp thêm luong đi bạn");
                            return;
                        }
                        p.upluongMessage(-100000);
//                        p.c.tieucoin += 20000;
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                        Item item = ItemTemplate.itemDefault(746, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
//                            Option op = new Option(3, 500);
//                            item.options.add(op);
//                            op = new Option(3, 500);
//                            item.options.add(op);
//                             op = new Option(6, 200);
//                            item.options.add(op);
//                             op = new Option(7, 200);
//                            item.options.add(op);
//                             op = new Option(12, 100);
//                            item.options.add(op);
//                            op = new Option(18, 100);
//                            item.options.add(op);
//                            op = new Option(27, 9);
//                            item.options.add(op);
//                            op = new Option(28, 5);
//                            item.options.add(op);
//                            op = new Option(29, 200);
//                            item.options.add(op);
//                            op = new Option(33, 100);
//                            item.options.add(op);
//                            op = new Option(48, 500);
//                            item.options.add(op);
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(95, 220);
//                            item.options.add(op);
//                            op = new Option(79, 35);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k luong.và mua món đồ"+ItemTemplate.ItemTemplateId(746).name);
                        break;
                    }
                    case 1: {//áo
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k luong.hãy nạp thêm luong đi bạn");
                            return;
                        }
//                        p.luong -= 10000;
                        p.upluongMessage(-100000);
//                        p.c.tieucoin += 20000;
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(712, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(91, 220);
//                            item.options.add(op);
//                            op = new Option(80, 300);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k luong.và mua món đồ"+ItemTemplate.ItemTemplateId(712).name);
                        break;
                    }
                    case 2: {//găng
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k luong .hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                        p.upluongMessage(-100000);
//                        p.c.tieucoin += 20000;
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(747, true);
                           item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(94, 400);
//                            item.options.add(op);
//                            op = new Option(86, 800);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(747).name);
                        break;
                    }
                    case 3: {//quần
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
               p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(713, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(82, 2500);
//                            item.options.add(op);
//                            op = new Option(83, 2500);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(713).name);
                        break;
                    }
                    case 4: {//giày
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                       p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(748, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(82, 2500);
//                            item.options.add(op);
//                            op = new Option(84, 900);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(748).name);
                        break;
                    }
                    case 5: {//phù
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                        p.upluongMessage(-100000);
//                        p.c.tieucoin += 20000;
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(750, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                          
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(83, 2500);
//                            item.options.add(op);
//                            op = new Option(84, 900);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(750).name);
                        break;
                    }
                    case 6: {//bội
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
              p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(751, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                         
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(87, 2000);
//                            item.options.add(op);
//                            op = new Option(96, 220);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(751).name);
                        break;
                    }
                    case 7: {//quần
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy nạp thêm lượng đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                   p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(749, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                          
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(92, 220);
//                            item.options.add(op);
//                            op = new Option(96, 220);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad .và mua món đồ"+ItemTemplate.ItemTemplateId(749).name);
                        break;
                    }
                    case 8: {//chuyền
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                 p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(752, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                          
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(79, 35);
//                            item.options.add(op);
//                            op = new Option(81, 35);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(752).name);
                        break;
                    }
                    case 9: {
                        if (p.luong < 200000) {
                            p.conn.sendMessageLog("bạn ko có 200k lượng.hãy nạp thêm coin đi bạn");
                            return;
                        }
//                        p.luong -= 200000;
                  p.upluongMessage(-200000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                        Item item = ItemTemplate.itemDefault(711, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                            Option op = new Option(3, 500);
                            item.options.add(op);
//                            op = new Option(3, 500);
//                            item.options.add(op);
                            op = new Option(87, 10000);
                            item.options.add(op);
                             op = new Option(6, 2000);
                            item.options.add(op);
                             op = new Option(7, 2000);
                            item.options.add(op);
                             op = new Option(12, 200);
                            item.options.add(op);
                            op = new Option(18, 200);
                            item.options.add(op);
                            op = new Option(27, 18);
                            item.options.add(op);
                            op = new Option(28, 10);
                            item.options.add(op);
                            op = new Option(29, 2000);
                            item.options.add(op);
                            op = new Option(33, 200);
                            item.options.add(op);
                            op = new Option(48, 1000);
                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 200k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(711).name);
                        break;
                    }
                    }
                break;
                    }
            
            
            case 2: {
                switch (b3) {
                    case 0: {//nón
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                     p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(753, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(95, 220);
//                            item.options.add(op);
//                            op = new Option(79, 35);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(753).name);
                        break;
                    }
                    case 1: {//áo
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy nạp thêm  đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
               p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(715, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(91, 220);
//                            item.options.add(op);
//                            op = new Option(80, 300);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(715).name);
                        break;
                    }
                    case 2: {//găng
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm luogjw đi bạn");
                            return;
                        }
                        
//                        p.luong -= 100000;
p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(754, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                            
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(94, 400);
//                            item.options.add(op);
//                            op = new Option(86, 800);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(754).name);
                        break;
                    }
                    case 3: {//quần
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm lượng đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
               p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(716, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                            
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(82, 2500);
//                            item.options.add(op);
//                            op = new Option(83, 2500);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(716).name);
                        break;
                    }
                    case 4: {//giày
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có lượng.hãy nạp thêm lượng đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(755, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            item.options.add(op);
//                            op = new Option(82, 2500);
//                            item.options.add(op);
//                            op = new Option(84, 900);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng . mua món đồ"+ItemTemplate.ItemTemplateId(755).name);
                        break;
                    }
                    case 5: {//phù
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm lượng đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                      p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(757, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                            
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(83, 2500);
//                            item.options.add(op);
//                            op = new Option(84, 900);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(757).name);
                        break;
                    }
                    case 6: {//bội
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm coin đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(758, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(87, 2000);
//                            item.options.add(op);
//                            op = new Option(96, 220);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(758).name);
                        break;
                    }
                    case 7: {//quần
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng.hãy nạp thêm coin đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
                     p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(756, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                            
//                            item.options.add(op);
//                            op = new Option(92, 220);
//                            item.options.add(op);
//                            op = new Option(96, 220);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(756).name);
                        break;
                    }
                    case 8: {//chuyền
                        if (p.luong < 100000) {
                            p.conn.sendMessageLog("bạn ko có 100k lượng .hãy đi up thêm lượng đi bạn");
                            return;
                        }
//                        p.luong -= 100000;
             p.upluongMessage(-100000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                       Item item = ItemTemplate.itemDefault(759, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                           
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(79, 35);
//                            item.options.add(op);
//                            op = new Option(81, 35);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 100k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(759).name);
                        break;
                    }
                    case 9: {
                        if (p.luong < 200000) {
                            p.conn.sendMessageLog("bạn ko có 200k lượng cook đi up đi ");
                            return;
                        }
//                        p.luong -= 200000;
  p.upluongMessage(-200000);
                        SQLManager.stat.executeUpdate("UPDATE `player` SET `luong`=" + p.luong + " WHERE `id`=" + p.id + " LIMIT 1;");
                        Item item = ItemTemplate.itemDefault(714, true);
                            item.quantity = 1;
                            item.upgrade = 16;
                            item.isLock = true;
                            Option op = new Option(3, 500);
                            item.options.add(op);
//                            op = new Option(3, 500);
//                            item.options.add(op);
                            op = new Option(87, 10000);
                            item.options.add(op);
                             op = new Option(6, 2000);
                            item.options.add(op);
                             op = new Option(7, 2000);
                            item.options.add(op);
                             op = new Option(12, 200);
                            item.options.add(op);
                            op = new Option(18, 200);
                            item.options.add(op);
                            op = new Option(27, 18);
                            item.options.add(op);
                            op = new Option(28, 10);
                            item.options.add(op);
                            op = new Option(29, 2000);
                            item.options.add(op);
                            op = new Option(33, 200);
                            item.options.add(op);
                            op = new Option(48, 1000);
                            item.options.add(op);
//                            op = new Option(85, 9);
//                            item.options.add(op);
//                            op = new Option(79, 35);
//                            item.options.add(op);
//                            op = new Option(81, 35);
//                            item.options.add(op);
                            p.c.addItemBag(false, item);
                          Service.chatKTG( "Cảm ơn " + p.c.name + " đã ủng hộ ad 200k lượng.và mua món đồ"+ItemTemplate.ItemTemplateId(714).name);
                        break;
                    }
                    }
                break;
                    }
        }}
//        }}
public static void rolldo(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 1000000) {
                    p.conn.sendMessageLog("Cần 1tr lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 2000) {
                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
                    break;
                }
                p.c.removeItemBags(1011, 2000);
                p.upluongMessage(-1000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(998);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
            case 1: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 1000000) {
                    p.conn.sendMessageLog("Cần 1tr lượng");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 2000) {
                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
                    break;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.c.removeItemBags(1011, 2000);
                p.upluongMessage(-1000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(865);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 2: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 1000000) {
                    p.conn.sendMessageLog("Cần 1tr lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 2000) {
                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
                    break;
                }
                p.c.removeItemBags(1011, 2000);
                p.upluongMessage(-1000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(866);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 3: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 1000000) {
                    p.conn.sendMessageLog("Cần 1tr lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 2000) {
                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
                    break;
                }
                p.c.removeItemBags(1011, 2000);
                p.upluongMessage(-1000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(989);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 4: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 1000000) {
                    p.conn.sendMessageLog("Cần 1tr lượng");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 2000) {
                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
                    break;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.c.removeItemBags(1011, 2000);
                p.upluongMessage(-1000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(999);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 5: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 2000) {
                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
                    break;
                }
                if (p.luong < 1000000) {
                    p.conn.sendMessageLog("Cần 1tr lượng");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.c.removeItemBags(1011, 2000);
                p.upluongMessage(-1000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(1000);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
                case 6: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 1500000) {
                    p.conn.sendMessageLog("Cần 1500k lượng lượng");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 3000) {
                    p.conn.sendMessageLog("Con phải mang 3k nguyệt nhãn đến cho ta");
                    break;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.c.removeItemBags(1011, 3000);
                p.upluongMessage(-1500000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(933);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
                case 7: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.luong < 300000 ) {
                    p.conn.sendMessageLog("Cần 300k lượng lượng");
                    return;
                }
                if (p.c.quantityItemyTotal(1011) < 100) {
                    p.conn.sendMessageLog("Con phải mang 100 nguyệt nhãn đến cho ta");
                    break;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
                p.c.removeItemBags(1011, 100);
                p.upluongMessage(-300000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(839);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
                case 8: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.xu < 1000000000) {
                    p.conn.sendMessageLog("Cần 1 tỷ xu");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
//                if (p.c.quantityItemyTotal(1011) < 2000) {
//                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
//                    break;
//                }
//                p.c.removeItemBags(1011, 2000);
                p.c.upxuMessage(-1000000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(931);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;
            }
            case 9: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.xu < 1500000000) {
                    p.conn.sendMessageLog("Cần 1b5 xu");
                    return;
                }
//                if (p.c.quantityItemyTotal(1011) < 2000) {
//                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
//                    break;
//                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
//                p.c.removeItemBags(1011, 2000);
                p.c.upxuMessage(-1500000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(999);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 10: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.xu < 1000000000) {
                    p.conn.sendMessageLog("Cần 2b xu ");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
//                if (p.c.quantityItemyTotal(1011) < 2000) {
//                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
//                    break;
//                }
//                p.c.removeItemBags(1011, 2000);
                p.c.upxuMessage(-1000000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(866);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 11: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.xu < 2000000000) {
                    p.conn.sendMessageLog("Cần 2b xu");
                    return;
                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
//                if (p.c.quantityItemyTotal(1011) < 2000) {
//                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
//                    break;
//                }
//                p.c.removeItemBags(1011, 2000);
                p.c.upxuMessage(-2000000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(1010);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 12: {
                if (p.c.isNhanban) {
                    p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.xu < 500000000) {
                    p.conn.sendMessageLog("Cần 2b xu");
                    return;
                }
//                if (p.c.quantityItemyTotal(1011) < 2000) {
//                    p.conn.sendMessageLog("Con phải mang 2k nguyệt nhãn đến cho ta");
//                    break;
//                }
                if (p.c.getBagNull() < 1) {
                    p.conn.sendMessageLog("Hành trang không đủ chỗ trống");
                    return;
                }
//                p.c.removeItemBags(1011, 2000);
                p.c.upxuMessage(-500000000);
//                p.c.tieucoin += 100000;
                Item itemup = ItemTemplate.itemDefault(1018);
                itemup.isExpires = false;
                itemup.isLock = true;
                itemup.upgrade = 16;
                itemup.quantity = 1;
                p.c.addItemBag(true, itemup);
                break;

            }
            case 13: {
                if (p.luong < 1000000) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Cần 1 triệu lượng mới có thể nâng cấp");
                    break;
                }
                if (p.c.quantityItemyTotal(694) < 1) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Con phải mang mắt 10 đến để đổi mắt 16");
                    break;
                }
                if (p.c.quantityItemyTotal(1006) < 10000) {
                    p.conn.sendMessageLog("Bạn phải thu thập đủ 10000 mảnh ghép để triệu hồi quạ bí ẩn");
                    break;
                }
                if (p.c.quantityItemyTotal(694) < 1) {
                    Service.chatNPC(p, Short.valueOf(npcid), "Con phải mang mắt 10 đến để đổi mắt 16");
                    break;
                }
                p.c.removeItemBags(1006, 10000);
                p.c.removeItemBags(694, 1);
                p.upluongMessage(-1000000L);
                p.sendAddchatYellow("Bạn nhận được con mắt mangekyou sharingan của Gia tộc Uchihahaha");
                Item itemup = ItemTemplate.itemDefault(1005);
                itemup.upgrade = 16;
                p.c.addItemBag(true, itemup);
                break;
            }
                case 14: {
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- Để Đổi Đồ VIP con cần mảnh nguyệt nhãn đi up ở lc ltt , map vip n/"
                                        + "n/"
                                        + " Và 1 Ít Ngân Lượng 500k-1m5 lg.\n"
                                + " Những đồ có ghi giá là đồ chỉ đổi = xu.\n"
                                + " Cái cuối cùng là đổi mắt k cần nguyệt nhãn hay xu gì hết đủ nl là đổi được.\n"
                        );
                        break;
        }
    }
}
    public static void ThucTinh(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    break;
                }
                switch (b3) {
                    case 1: {
                        byte i;
                        Item it = p.c.ItemBody[1];
                        if (p.c.get().isNhanban) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.quantityItemyTotal(837) < 100) {
                            Service.chatNPC(p, (short) npcid, "Cần 100 Đá Thức Tỉnh");
                            return;
                        }
                        if (p.c.ItemBody[1] == null) {
                            Service.chatNPC(p, (short) npcid, "Hãy Mặc Vũ Khí Vào Trước ");
                            return;
                        }
                        if (p.luong < 10000) {
                            Service.chatNPC(p, (short) npcid, "Cần 10.000 Lượng");
                            return;
                        }
                        if (p.c.xu < 10000000) {
                            Service.chatNPC(p, (short) npcid, "Cần 10.000.000 Xu");
                            return;
                        }
                        if (it.upgrade < 16) {
                            Service.chatNPC(p, (short) npcid, "Vũ Khí +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        for (i = 0; i < it.options.size(); ++i) {
                            if (it.options.get(i).id == 67) {
                                Service.chatNPC(p, (short) npcid, " Vũ Khí Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        if (Util.nextInt(100) < 1) {
                            Option op = new Option(67, Util.nextInt(50, 100));
                            p.c.ItemBody[1].options.add(op);
                            Service.chatNPC(p, (short) npcid, "Thức Tỉnh Thành Công");
                            Manager.serverChat("Thông Báo", " Chúc Mừng " + p.c.name + " Đã Thức Tỉnh Vũ Khí Thành Công");
                        } else {
                            Service.chatNPC(p, (short) npcid, "Thức Tỉnh Thất Bại");
                        }
                        p.c.removeItemBags(837, 100);
                        p.upluongMessage(-10000);
                        p.c.upxuMessage(-10000000);
                        break;
                    }
                    case 10: {
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- Để Thức Tỉnh Con Cần Có Đá Thức Tỉnh Và 1 Ít Ngân Lượng.\n"
                                + "- Khi Thức Tỉnh Thành Công , Trang Bị Của Con Sẽ Trở Nên Mạnh Hơn.\n"
                                + "- Thức Tỉnh Vũ Khí Cần : 10.000 Lượng + 10.000.000 Xu + 100 Đá Thức Tỉnh ( Chỉ Số Ẩn + Thêm : 50% - 100% Tấn Công Khi Đánh Chí Mạng.\n"
                        );
                        break;
                    }
                    default:
                        Service.chatNPC(p, (short) npcid, "Đang Cập Nhật.");
                        break;
                }
                break;
            }
        }
    }
}
