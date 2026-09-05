package server;

import Menu.AnToc;
import Menu.EventName;
import Menu.MenuEvent;
import Menu.MenuTaiXiu;
import Menu.TuTien;
import Menu.Upgrade;
import Menu.Vip;
import NPC.NPCHandle;
import NPC.NPCID;
import Upgrade.BiKip;
import Upgrade.KhaiHoa;
import Upgrade.ThucTinh;
import Upgrade.UpgradeBuaNo;
import Upgrade.UpgradeNhanThuatGiaToc;
import Upgrade.UpgradePet;
import Upgrade.UpgradeYoroi;
import stream.Client;
import stream.Server;
import stream.SaveData;
import assembly.Admission;
import assembly.Player;
import io.Message;
import io.SQLManager;

import java.sql.ResultSet;

public class Menu {

    public static void menuId(Player p, Message ms) {
        try {
            byte typeClose = 0;
            if (p.conn.version >= 205) {
                typeClose = ms.reader().readByte();
            }
            final short npcId;
            if (p.conn.version >= 205) {
                npcId = ms.reader().readByte();
            } else {
                npcId = ms.reader().readShort();
            }
            ms.cleanup();
            p.c.typemenu = 0;
            p.typemenu = npcId;
            switch (npcId) {
                case 33:
                    switch (Server.manager.event) {
                        case EventName.TRUNG_THU: {//trung thu
                            Service.doMenuArray(p, new String[]{"Bánh thập cẩm", "Bánh dẻo", "Bánh đậu xanh", "Bánh pía", "Hộp bánh thường", "Hộp bánh thượng hạng","Top","Hướng Dẫn"});
                            break;
                        }
                        case EventName.HALLOWEEN://haloween
                            Service.doMenuArray(p, new String[]{"Làm Hộp ma quỷ", "Làm Kẹo táo", "Top", "Hướng Dẫn "});
                            break;
                        case 5:
                            Service.doMenuArray(p, new String[]{"Làm Tre Xanh Tram Dot", "Làm Tre Vang Tram Dot", "Đổi 200 Tre Vang Lấy Hỏa Long", "Top", "Hướng Dẫn "});
                            break;
                        case 7://giỗ tổ
                            Service.doMenuArray(p, new String[]{"Làm Dưa Hấu Dài", "Làm Dưa Hấu Tròn", "Top", "Hướng dẫn"});
                            break;
                        case EventName.VU_LAN://he
                            Service.doMenuArray(p, new String[]{"Làm Bỏ Sen Trắng", "Làm Bỏ Sen Hồng", "Top", "Hướng Dẫn "});
                            break;
                        case EventName.SK_NOEL:
                            Service.doMenuArray(p, new String[]{"Làm Bánh Dâu Tây", "Làm Bánh Chocolate", "Top", "Hướng Dẫn"});
                            break;
                        case EventName.SK_TET_NGUYEN_DAN:
                            Service.doMenuArray(p, new String[]{"Làm Bánh Tét", "Làm Bánh Chưng", "Làm Pháo", "Top", "Hướng Dẫn"});
                            break;
                        default: {
                            break;
                        }
                    }
                    break;
                case 40:
                    switch (p.c.mapid) {
                        case 117: {
                            if (p.c.get().level < 60) {
                            p.conn.sendMessageLog("Yêu Cầu Trình Độ Cấp 60");
                            return;
                        }
                            Service.doMenuArray(p, new String[]{"Rời khỏi nơi này", "Đặt cược", "Hướng dẫn"});
                            break;
                        }
                        case 118:
                        case 119: {
                            Service.doMenuArray(p, new String[]{"Rời khỏi nơi này", "Thông tin"});
                            break;
                        }
                    }
                    break;
                
                default:
                    break;
            }
            ms = new Message((byte) 40);
            ms.writer().flush();
            p.conn.sendMessage(ms);
            ms.cleanup();
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (ms != null) {
                ms.cleanup();
            }
        }
    }

    public static void menu(Player p, Message ms) {
        try {
            byte typeClose = 0;
            if (p.conn.version >= 205) {
                typeClose = ms.reader().readByte();
            }
            final byte npcId = ms.reader().readByte();
            byte menuId = ms.reader().readByte();
            byte b3 = ms.reader().readByte();
            ms.cleanup();
            if (p.conn.version >= 205 && npcId != 28) {
                ++NPCID.count;
                if (NPCID.count < 2) {
                    b3 = -1;
                }
                if (NPCID.count >= 2) {
                    NPCID.count = 0;
                }
            }
            if ((p.typemenu == -1 || p.typemenu == 0) && p.typemenu != npcId) {
                switch (npcId) {
                    case 0:
                        NPCID.npcKanata(p, npcId, menuId, b3);
                        break;
                    case 1:
                        NPCID.npcFuroya(p, npcId, menuId, b3);
                        break;
                    case 2:
                        NPCID.npcAmeji(p, npcId, menuId, b3);
                        break;
                    case 3:
                        NPCID.npcKiriko(p, npcId, menuId, b3);
                        break;
                    case 4:
                        NPCID.npcTabemono(p, npcId, menuId, b3);
                        break;
                    case 5:
                        NPCID.npcKamakura(p, npcId, menuId, b3);
                        break;
                    case 6:
                        NPCID.npcKenshinto(p, npcId, menuId, b3);
                        break;
                    case 7:
                        NPCID.npcUmayaki_Lang(p, npcId, menuId, b3);
                        break;
                    case 8:
                        NPCID.npcUmayaki_Truong(p, npcId, menuId, b3);
                        break;
                    case 9:
                        NPCID.npcToyotomi(p, npcId, menuId, b3);
                        break;
                    case 10:
                        NPCID.npcOokamesama(p, npcId, menuId, b3);
                        break;
                    case 11:
                        NPCID.npcKazeto(p, npcId, menuId, b3);
                        break;
                    case 12:
                        NPCID.npcTajima(p, npcId, menuId, b3);
                        break;
                    case 18:
                        NPCID.npcRei(p, npcId, menuId, b3);
                        break;
                    case 19:
                        NPCID.npcKirin(p, npcId, menuId, b3);
                        break;
                    case 20:
                        NPCID.npcSoba(p, npcId, menuId, b3);
                        break;
                    case 21:
                        NPCID.npcSunoo(p, npcId, menuId, b3);
                        break;
                    case 22:
                        NPCID.npcGuriin(p, npcId, menuId, b3);
                        break;
                    case 23:
                        NPCID.npcMatsurugi(p, npcId, menuId, b3);
                        break;
                    case 24:
                        NPCID.npcOkanechan(p, npcId, menuId, b3);
                        break;
                    case 25:
                        NPCID.npcRikudou(p, npcId, menuId, b3);
                        break;
                    case 26:
                        NPCID.npcGoosho(p, npcId, menuId, b3);
                        break;
                    case 27:
                        NPCID.npcTruCoQuan(p, npcId, menuId, b3);
                        break;
                    case 28:
                        NPCID.npcShinwa(p, npcId, menuId, b3);
                        break;
                    case 29:
                        NPCID.npcChiHang(p, npcId, menuId, b3);
                        break;
                    case 30:
                        NPCID.npcRakkii(p, npcId, menuId, b3);
                        break;
                    case 31:
                        break;
                    case 32:
                        NPCID.npcKagai(p, npcId, menuId, b3);
                        break;
                    case 33:
                        MenuEvent.npcTienNu(p, npcId, menuId, b3);
                        break;
                    case 34:
                        break;
                    case 35:
                        break;
                    case 36:
                        NPCID.npcVuaHung(p, npcId, menuId, b3);
                        break;
                    case 37:
                        NPCID.npcKanata_LoiDai(p, npcId, menuId, b3);
                        break;
                    case 38:
                        NPCID.npcAdmin(p, npcId, menuId, b3);
                        break;
                    case 39: {
                        NPCID.npcRikudou_ChienTruong(p, npcId, menuId, b3);
                        break;
                    }
                    case 40: {
                        NPCID.npcKagai_GTC(p, npcId, menuId, b3);
                        break;
                    }
                    case 42: {
                        break;
                    }
                    case 45: {
                        BiKip.MenuUpgradeBiKip(p, npcId, menuId, b3);
                        break;
                    }
                    case 46: {
                        AnToc.MenuAnToc(p, npcId, menuId, b3);
                        break;
                    }
                    case 47: {
                        UpgradeYoroi.MenuUpgradeYoroi(p, npcId, menuId, b3);
                        break;
                    }
                    case 48: {
                        NPCID.npctest(p, npcId, menuId, b3);
                        break;
                    }
                    case 49: {
                        ThucTinh.ThucTinh(p, npcId, menuId, b3);
                        break;
                    }
                    case 50: {
                        NPCID.npcdoixe(p, b3, npcId, menuId);
                    }
//                    case 51: {
//                        MenuTaiXiu.MenuTaiXiu(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 52: {
                        break;
                    }
                    case 54: {
                        NPCID.npcshopjarai(p, npcId, menuId, b3);
                        break;
                    }
//                    case 57: {
//                        NPCID.VeBua(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 57: {
                        NPCID.npcVip(p, npcId, menuId, b3);
                        break;
                    }
//                    case 57: {
//                        Vip.MenuVip(p, npcId, menuId, b3);
//                        break;
//
//                    }
                    case 58: {
                        TuTien.npcTuTien(p, npcId, menuId, b3);
                        break;
                    }
                    case 59: {
                        UpgradePet.MenuUpgradePet(p, npcId, menuId, b3);
                        break;
                    }
                    case 60: {
                        NPCID.rolldo(p, npcId, menuId, b3);
                        break;
                    }
//                    case 61: {
//                        NPCID.npcchuyenphai(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 62: {
                        NPCID.dunghop(p, npcId, menuId, b3);
                        break;
                    }
                    case 64: {
                        NPCID.npcNangCap(p, npcId, menuId, b3);
                        break;
                    }
                    case 65: {
                        NPCID.cs(p, npcId, menuId, b3);
                        break;
                    }
                    case 68: {
                        NPCID.npcnew(p, npcId, menuId, b3);
                        break;
                    }
                    case 67: {
                        NPCID.npccaychay(p, npcId, menuId, b3);
                        break;
                    }
                    case 69: {
                        NPCID.MapBoss(p, npcId, menuId, b3);
                        break;
                    }

                    case 120: {
                        if (menuId > 0 && menuId < 7) {
                            Admission.Admission(p, menuId);
                        }
                    }
                    default: {
                        Service.chatNPC(p, (short) npcId, "Chức năng này đang được cập nhật");
                        break;
                    }
                }
            } else if (p.typemenu == npcId) {
                if (p.conn.version > 203 && p.typemenu != 28 && p.conn.zoomLevel != 1) {
                    b3 = -1;
                }
                switch (p.typemenu) {
                    case 0:
                        NPCID.npcKanata(p, npcId, menuId, b3);
                        break;
                    case 1:
                        NPCID.npcFuroya(p, npcId, menuId, b3);
                        break;
                    case 2:
                        NPCID.npcAmeji(p, npcId, menuId, b3);
                        break;
                    case 3:
                        NPCID.npcKiriko(p, npcId, menuId, b3);
                        break;
                    case 4:
                        NPCID.npcTabemono(p, npcId, menuId, b3);
                        break;
                    case 5:
                        NPCID.npcKamakura(p, npcId, menuId, b3);
                        break;
                    case 6:
                        NPCID.npcKenshinto(p, npcId, menuId, b3);
                        break;
                    case 7:
                        NPCID.npcUmayaki_Lang(p, npcId, menuId, b3);
                        break;
                    case 8:
                        NPCID.npcUmayaki_Truong(p, npcId, menuId, b3);
                        break;
                    case 9:
                        NPCID.npcToyotomi(p, npcId, menuId, b3);
                        break;
                    case 10:
                        NPCID.npcOokamesama(p, npcId, menuId, b3);
                        break;
                    case 11:
                        NPCID.npcKazeto(p, npcId, menuId, b3);
                        break;
                    case 12:
                        NPCID.npcTajima(p, npcId, menuId, b3);
                        break;
                    case 18:
                        NPCID.npcRei(p, npcId, menuId, b3);
                        break;
                    case 19:
                        NPCID.npcKirin(p, npcId, menuId, b3);
                        break;
                    case 20:
                        NPCID.npcSoba(p, npcId, menuId, b3);
                        break;
                    case 21:
                        NPCID.npcSunoo(p, npcId, menuId, b3);
                        break;
                    case 22:
                        NPCID.npcGuriin(p, npcId, menuId, b3);
                        break;
                    case 23:
                        NPCID.npcMatsurugi(p, npcId, menuId, b3);
                        break;
                    case 24:
                        NPCID.npcOkanechan(p, npcId, menuId, b3);
                        break;
                    case 25:
                        NPCID.npcRikudou(p, npcId, menuId, b3);
                        break;
                    case 26:
                        NPCID.npcGoosho(p, npcId, menuId, b3);
                        break;
                    case 27:
                        NPCID.npcTruCoQuan(p, npcId, menuId, b3);
                        break;
                    case 28:
                        NPCID.npcShinwa(p, npcId, menuId, b3);
                        break;
                    case 29:
                        NPCID.npcChiHang(p, npcId, menuId, b3);
                        break;
                    case 30:
                        NPCID.npcRakkii(p, npcId, menuId, b3);
                        break;
                    case 31:
                        break;
                    case 32:
                        NPCID.npcKagai(p, npcId, menuId, b3);
                        break;
                    case 33:
                        MenuEvent.npcTienNu(p, npcId, menuId, b3);
                        break;
                    case 34:
                        break;
                    case 35:
                        break;
                    case 36:
                        NPCID.npcVuaHung(p, npcId, menuId, b3);
                        break;
                    case 37:
                        NPCID.npcKanata_LoiDai(p, npcId, menuId, b3);
                        break;
                    case 38:
                        NPCID.npcAdmin(p, npcId, menuId, b3);
                        break;
                    case 39: {
                        NPCID.npcRikudou_ChienTruong(p, npcId, menuId, b3);
                        break;
                    }
                    case 40: {
                        NPCID.npcKagai_GTC(p, npcId, menuId, b3);
                        break;
                    }
                    case 42: {
                        break;
                    }
                    case 45: {
                        BiKip.MenuUpgradeBiKip(p, npcId, menuId, b3);
                        break;
                    }
                    case 46: {
                        AnToc.MenuAnToc(p, npcId, menuId, b3);
                        break;
                    }
                    case 47: {
                        UpgradeYoroi.MenuUpgradeYoroi(p, npcId, menuId, b3);
                        break;
                    }
                    case 48: {
                        NPCID.npctest(p, npcId, menuId, b3);
                        break;
                    }
                    case 49: {
                        ThucTinh.ThucTinh(p, npcId, menuId, b3);
                        break;
                    }
                    case 50: {
                        NPCID.npcdoixe(p, b3, npcId, menuId);
                    }
//                    case 51: {
//                        MenuTaiXiu.MenuTaiXiu(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 52: {
                        break;
                    }
                    case 54: {
                        NPCID.npcshopjarai(p, npcId, menuId, b3);
                        break;
                    }
//                    case 55: {
//                        KhaiHoa.(p, npcId, menuId, b3);
//                        break;
//                    }
//                    case 56: {
//                        NPCID.VeBua(p, npcId, menuId, b3);
//                        break;
//                    }
//                    case 57: {
//                        Vip.MenuVip(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 57: {
                        NPCID.npcVip(p, npcId, menuId, b3);
                        break;
                    }
                    case 58: {
                        TuTien.npcTuTien(p, npcId, menuId, b3);
                        break;
                    }
                    case 59: {
                        UpgradePet.MenuUpgradePet(p, npcId, menuId, b3);
                        break;
                    }
                    case 60: {
                        NPCID.rolldo(p, npcId, menuId, b3);
                        break;
                    }
//                    case 61: {
//                        NPCID.npcchuyenphai(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 62: {
                        NPCID.dunghop(p, npcId, menuId, b3);
                        break;
                    }
                   case 64: {
                        NPCID.npcNangCap(p, npcId, menuId, b3);
                        break;
                    }
//                    case 64: {
//                        UpgradeNhanThuatGiaToc.UpgradeNhanThuatGiaToc(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 65: {
                        NPCID.cs(p, npcId, menuId, b3);
                        break;
                    }
//                    case 66: {
//                        NPCID.npcdoimn(p, npcId, menuId, b3);
//                        break;
//                    }
                    case 67: {
                        NPCID.npccaychay(p, npcId, menuId, b3);
                        break;
                    }
                    case 68: {
                        NPCID.npcnew(p, npcId, menuId, b3);
                        break;
                    }
                    case 69: {
                        NPCID.MapBoss(p, npcId, menuId, b3);
                        break;
                    }
                    //
                    case 120: {
                        if (menuId > 0 && menuId < 7) {
                            Admission.Admission(p, (byte) menuId);
                        }
                    }
                    break;
                }
            } else {
                switch (p.typemenu) {
                    case -125: {
                        NPCHandle.doiGiayVun(p, npcId, menuId, b3);
                        break;
                    }
                    case 92: {
                        switch (menuId) {
                            case 0: {
                                if (p.c.get().level < 60) {
                                    p.conn.sendMessageLog("Yêu Cầu Trình Độ Cấp 60");
                                    return;
                                }
                                Server.manager.rotationluck[0].luckMessage(p);
                                break;
                            }
                            case 1: {
                                if (p.c.get().level < 60) {
                                    p.conn.sendMessageLog("Yêu Cầu Trình Độ Cấp 60");
                                    return;
                                }
                                Server.manager.rotationluck[1].luckMessage(p); // vxmm thường
                                break;
                            }
                        }
                        break;
                    }
                    case 9999: {
                        if (p.role != 10102003) {
                            p.lockAcc();
                            return;
                        }
                        Menu.menuAdmin(p, npcId, menuId, b3);
                        break;
                    }
                    case 125:
                        Menu.cpanel(p, npcId, menuId, b3);
                        break;
                    default: {
                        break;
                    }
                }
            }
            p.typemenu = 0;
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (ms != null) {
                ms.cleanup();
            }
        }
    }

    public static void cpanel(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                Service.sendInputDialog(p, (short) 1010, "Tên nhân vật:");
                break;
            }
            case 1: {
                Service.sendInputDialog(p, (short) 1012, "Tên nhân vật:");
                break;
            }
        }
    }

    public static void menuAdmin(Player p, byte npcid, byte menuId, byte b3) {
        Player player;
        int i;
        switch (menuId) {
            case 0: {
                Service.sendInputDialog(p, (short) 9998, "Nhập số phút muốn bảo trì 0->10 (0: ngay lập tức)");
                break;
            }
            case 1: {
                Service.KhoaTaiKhoan(p);
                break;
            }
            case 2: {
                Service.AutoSaveData();
                p.sendAddchatYellow("Update thành công");
                break;
            }
            case 3: {
                String chat = "MapID: " + p.c.mapid + " - X: " + p.c.get().x + " - Y: " + p.c.get().y;
                p.conn.sendMessageLog(chat);
                break;
            }
            case 4: {
                Service.sendInputDialog(p, (short) 9996, "Nhập số xu muốn cộng (có thể nhập số âm)");
                break;
            }
            case 5: {
                Service.sendInputDialog(p, (short) 9995, "Nhập số lượng muốn cộng (có thể nhập số âm)");
                break;
            }
            case 6: {
                Service.sendInputDialog(p, (short) 9997, "Nhập số yên muốn cộng (có thể nhập số âm)");
                break;
            }
            case 7: {
                Service.sendInputDialog(p, (short) 9994, "Nhập số level muốn tăng (có thể nhập số âm)");
                break;
            }
            case 8: {
                Service.sendInputDialog(p, (short) 9993, "Nhập số tiềm năng muốn tăng (có thể nhập số âm)");
                break;
            }
            case 9: {
                Service.sendInputDialog(p, (short) 9992, "Nhập số kỹ năng muốn tăng (có thể nhập số âm)");
                break;
            }
            case 10: {
                SaveData saveData = new SaveData();
                saveData.player = p;
                Thread t1 = new Thread(saveData);
                t1.start();
                if (!Manager.isSaveData) {
                    player = null;
                    t1 = null;
                    saveData = null;
                }
                break;
            }
            case 11: {
                Service.sendInputDialog(p, (short) 9991, "Nhập nội dung");
                break;
            }
            case 12: {
                try {
                    Server.manager.sendTB(p, "Kiểm tra", "- Tổng số kết nối: " + Client.gI().conns_size() + "\n\n- Số người đăng nhập: " + Client.gI().players_size() + "\n\n- TỔNG SỐ NGƯỜI CHƠI THỰC TẾ: " + Client.gI().ninja_size());
                } catch (Exception var11) {
                    var11.printStackTrace();
                }
                break;
            }
            case 13: {
                synchronized (Client.gI().conns) {
                    for (i = 0; i < Client.gI().conns.size(); ++i) {
                        Session conn = (Session) Client.gI().conns.get(i);
                        if (conn != null) {
                            player = conn.player;
                            if (player != null) {
                                if (player.c == null) {
                                    Client.gI().kickSession(conn);
                                }
                            } else if (player == null) {
                                Client.gI().kickSession(conn);
                            }
                        }
                    }
                }

                p.conn.sendMessageLog("Dọn clone thành công!");
                break;
            }
            case 14: {
                synchronized (Client.gI().conns) {
                    for (i = 0; i < Client.gI().conns.size(); ++i) {
                        player = ((Session) Client.gI().conns.get(i)).player;
                        if (player != null && player != p) {
                            Client.gI().kickSession(player.conn);
                        }
                    }
                }

                p.conn.sendMessageLog("Dọn Session thành công!");
                break;
            }
            case 15: {
                Service.sendInputDialog(p, (short) 9990, "Nhập giá trị cần thay đổi");
                break;
            }
            case 16:
                break;

            case 17: {
                try {
                    ResultSet red = SQLManager.stat.executeQuery("SELECT * FROM `alert` WHERE `id` = 1;");
                    if (red != null && red.first()) {
                        String alert = red.getString("content");
                        Manager.alert.setAlert(alert);
                        red.close();
                    }
                    p.sendAddchatYellow("Cập nhật thông báo thành công");
                    Manager.alert.sendAlert(p);
                } catch (Exception e) {
                    p.conn.sendMessageLog("Lỗi cập nhật!");
                }
                break;
            }
        }
    }

}
