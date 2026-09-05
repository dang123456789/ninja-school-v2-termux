/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Menu;

import History.LichSu;
import Item.ItemName;
import Item.RandomItem;
import assembly.Item;
import assembly.Language;
import assembly.Option;
import assembly.Player;
import static assembly.UseItem.HanSuDung;
import io.Util;
import server.Manager;
import server.Rank;
import server.Service;
import stream.Server;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class MenuEvent {

    public static void npcTienNu(Player p, byte npcid, byte menuId, byte b3) {
        switch (Server.manager.event) {
                case 5: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        Service.sendInputDialog(p, (short) -21, "Nhập số lượng");
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) -22, "Nhập số lượng");
                        break;
                    case 2:
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog("Chức năng này không dành cho phân thân");
                            return;
                        }
                        if (p.c.quantityItemyTotal(ItemName.TRE_VANG_TRAM_DOT) < 200) {
                            Service.chatNPC(p, (short) npcid, "Không đủ TRE VANG TRAM DOT.");
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        p.c.removeItemBags(ItemName.TRE_VANG_TRAM_DOT, 200);
                        Item itemup = ItemTemplate.itemDefault(ItemName.HOA_LONG);
                        itemup.quantity = 1;
                        itemup.isLock = false;
                        itemup.isExpires = true;
                        itemup.expires = Util.TimeDay(7);
                        p.c.addItemBag(true, itemup);
                        break;
                    case 3:
                        Server.manager.sendTB(p, "Top", Rank.getStringBXH(4)
                                + "\n- Điểm của bạn: " + p.c.eventPoint);
                        break;
                    case 4:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- 10 ĐỐT TRE XANH(id:590) + 30.000 xu = 1 TRE XANH TRĂM ĐỐT(id:592) - ( không khóa)\n"
                                + "- 3 TRE XANH TRĂM ĐỐT(id:592) + 10 TÍN VẬT(id:595) + 50 Lượng = 1 TRE VÀNG TRĂM ĐỐT(id:593) - (đã khóa).\n"
                                + "- 200 GIỎ HOA = Hỏa Long (7 Ngày)\n"
                        );
                        break;
                    default: {
                        break;
                    }
                }
                break;
            }
            //Trung thu
            case EventName.TRUNG_THU: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0: {
                            Service.sendInputDialog(p, (short) -10, "Nhập số lượng");
                            break;
                    }
                    case 1: {
                            Service.sendInputDialog(p, (short) -11, "Nhập số lượng");
                            break;
                    }
                    case 2: {
                            Service.sendInputDialog(p, (short) -12, "Nhập số lượng");
                            break;
                    }
                    case 3: {
                            Service.sendInputDialog(p, (short) -13, "Nhập số lượng");
                            break;
                    }
                    case 4: {
                            Service.sendInputDialog(p, (short) -14, "Nhập số lượng");
                            break;
                    }
                    case 5: {
                            Service.sendInputDialog(p, (short) -15, "Nhập số lượng");
                            break;
                    }
                    case 6:
                        Server.manager.sendTB(p, "Top EVEN", Rank.getStringBXH(4)
                                + "\n- Điểm của bạn: " + p.c.eventPoint);
                        break;
                    case 7:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- Bánh thập cẩm = 1 Bột + 1 Trứng + 1 Hạt sen + 1 Đường + 1 Mứt + yên.\n"
                                + "- Bánh Dẻo = 1 Bột + 1 Hạt sen + 1 Đường + 1 Mứt + yên.\n"
                                + "- Bánh Đậu xanh = 1 Bột + 1 Trứng + 1 Đường + 1 Đậu xanh + yên.\n"
                                + "- Bánh pía = 1 Bột + 1 Trứng + 1 Đường + 1 Đậu xanh + yên.\n"
                                + "- Bánh pía = 1 Bột + 1 Trứng + 1 Đường + 1 Đậu xanh + yên.\n"
                                + "- Hộp bánh thường = 1 Bánh thập cẩm + 1 bánh dẻo + 1 bánh dậu xanh + 1 bánh pía + 10.000 Xu.\n"
                                + "- Hộp bánh thượng hạng = 1 Bánh thập cẩm + 1 bánh dẻo + 1 bánh dậu xanh + 1 bánh pía + 10 Lượng.\n"
                        );
                        break;
                    default: {
                        break;
                    }
                }
                break;
            }
            case EventName.SK_20_10: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        switch (b3) {
                            case 0:
                                Service.sendInputDialog(p, (short) -16, "Nhập số lượng");
                                break;
                            case 1:
                                Service.sendInputDialog(p, (short) -17, "Nhập số lượng");
                                break;
                            case 2:
                                Service.sendInputDialog(p, (short) -18, "Nhập số lượng");
                                break;
                        }
                        break;
                    case 1:
                        if (p.c.quantityItemyTotal(877) < 1
                                || p.c.quantityItemyTotal(878) < 1
                                || p.c.quantityItemyTotal(879) < 1) {
                            Service.chatNPC(p, (short) npcid, "Không đủ bó hoa mỗi loại để tặng.");
                            break;
                        }
                        if (p.c.getBagNull() < 1) {
                            Service.chatNPC(p, (short) npcid, Language.NOT_ENOUGH_BAG);
                            break;
                        }
                        p.c.removeItemBags(877, 1);
                        p.c.removeItemBags(878, 1);
                        p.c.removeItemBags(879, 1);
                        p.c.eventPoint += 1;
                        p.updateExp(20000000);
                        if (Util.nextInt(10) < 1) {
                            p.updateExp(Util.nextInt(15000000, 20000000));
                            return;
                        }
                        if (Util.nextInt(10) < 1) {
                            if (p.c.leveltutien >= 1 && p.c.leveltutien < 23) {
                                int ExpTuTien = Util.nextInt(10000000, 20000000);
                                p.c.exptutien += ExpTuTien;
                                p.sendAddchatYellow("Bạn nhận được " + ExpTuTien + " EXP Tu Tiên");
                                return;
                            } else {
                                p.updateExp(Util.nextInt(15000000, 20000000));
                            }
                        }
                        int itemID = RandomItem.TANG_HOA.next();
                        Item itm = ItemTemplate.itemDefault(itemID);
                        if (itemID == ItemName.PET_BONG_MA) {
                            if (Util.percent(150, 1)) {
                                Manager.chatKTG(p.c.name + " Tặng hoa đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                                itm.options.add(new Option(58, Util.nextInt(15, 20)));
                                itm.options.add(new Option(6, 5000));
                                itm.options.add(new Option(92, 80));
                                itm.options.add(new Option(94, 80));
                                itm.options.add(new Option(98, 10));
                                itm.options.add(new Option(67, 50));
                                itm.options.add(new Option(120, 1000));
                                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
                            } else {
                                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                                itm.isExpires = true;
                                itm.expires = Util.TimeDay(HSD);
                                itm.options.add(new Option(58, Util.nextInt(5, 15)));
                                itm.options.add(new Option(6, Util.nextInt(1000, 5000)));
                                itm.options.add(new Option(92, Util.nextInt(50, 80)));
                                itm.options.add(new Option(94, Util.nextInt(50, 80)));
                                itm.options.add(new Option(98, Util.nextInt(1, 10)));
                                itm.options.add(new Option(67, Util.nextInt(10, 50)));
                                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                            }
                        }
                        if (itemID == ItemName.PET_YEU_TINH) {
                            if (Util.percent(150, 1)) {
                                Manager.chatKTG(p.c.name + " Tặng hoa đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                                itm.options.add(new Option(87, 5000));
                                itm.options.add(new Option(6, 5000));
                                itm.options.add(new Option(92, 80));
                                itm.options.add(new Option(94, 80));
                                itm.options.add(new Option(98, 10));
                                itm.options.add(new Option(67, 50));
                                itm.options.add(new Option(120, 1000));
                                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
                            } else {
                                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                                itm.isExpires = true;
                                itm.expires = Util.TimeDay(HSD);
                                itm.options.add(new Option(87, Util.nextInt(1000, 5000)));
                                itm.options.add(new Option(6, Util.nextInt(1000, 5000)));
                                itm.options.add(new Option(92, Util.nextInt(50, 80)));
                                itm.options.add(new Option(94, Util.nextInt(50, 80)));
                                itm.options.add(new Option(98, Util.nextInt(1, 10)));
                                itm.options.add(new Option(67, Util.nextInt(10, 50)));
                                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                            }
                        }
                        if (itemID == ItemName.MAT_NA_JIRAI || itemID == ItemName.MAT_NA_JUMITO) {
                            if (Util.percent(150, 1)) {
                                Manager.chatKTG(p.c.name + " Tặng hoa đã nhận được " + ItemTemplate.ItemTemplateId(itemID).name + " vĩnh viễn");
                                itm.options.add(new Option(58, 20));
                                itm.options.add(new Option(80, 250));
                                itm.options.add(new Option(95, 100));
                                itm.options.add(new Option(96, 100));
                                itm.options.add(new Option(97, 100));
                                itm.options.add(new Option(67, 50));
                                itm.options.add(new Option(120, 1000));
                                LichSu.LichSuMoItemVinhVien(p.c.name, ItemTemplate.ItemTemplateId(itemID).name);
                            } else {
                                int HSD = HanSuDung[Util.nextInt(HanSuDung.length)];
                                itm.isExpires = true;
                                itm.expires = Util.TimeDay(HSD);
                                itm.options.add(new Option(58, Util.nextInt(10, 20)));
                                itm.options.add(new Option(80, Util.nextInt(100, 250)));
                                itm.options.add(new Option(95, Util.nextInt(50, 100)));
                                itm.options.add(new Option(96, Util.nextInt(50, 100)));
                                itm.options.add(new Option(97, Util.nextInt(50, 100)));
                                itm.options.add(new Option(67, Util.nextInt(10, 50)));
                                itm.options.add(new Option(120, Util.nextInt(500, 1000)));
                            }
                        }
                        p.c.addItemBag(true, itm);
                        break;
                    case 2:
                        Server.manager.sendTB(p, "Top Tặng Hoa", Rank.getStringBXH(4)
                                + "\n- Số Bó Hoa Đã Tặng: " + p.c.eventPoint);
                        break;
                    case 3:

                        break;
                    case 4:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- Bó Hoa Đỏ : 10 Hoa Hồng Đỏ.\n"
                                + "- Bó Hoa Vàng : 10 Hoa Hồng Vàng.\n"
                                + "- Bó Hoa Xanh : 10 Hoa Hồng Xanh.\n"
                                + "- Tặng Hoa Cần : 3 Bó Hoa Đỏ , Vàng , Xanh.\n"
                        );
                        break;
                    case 5:
                        switch (b3) {
                            case 0:
                                Server.manager.sendTB(p, "Phần thưởng Đua Top Tặng Hoa",
                                        "  - TOP 1 : 1 Pet TB1 10 chỉ số tự chọn vĩnh viễn + 1 Pet Bóng Ma Max Chỉ Số Vĩnh Viễn .\n"
                                        + "- Top 2 : 1 Pet TB1 8 chỉ số tự chọn vĩnh viễn.\n"
                                        + "- Top 3 : 1 Pet TB1 5 chỉ số tự chọn vĩnh viễn.\n"
                                        + "- Top 4 tới top 10 : 1 Mặt Nạ Jirai Hoặc Jumito New Options Vĩnh Viễn ( X2 Yên ).\n"
                                );
                                break;
                            case 1:
                                Server.manager.sendTB(p, "Phần thưởng Đua Top Nạp Tiền",
                                        "  - TOP 1 : 10 Chỉ Số Tự Chọn Thêm Vào Trang Bị Đang Mặc ( Chỉ Số X2 Chỉ Số Top Tặng Hoa ).\n"
                                        + "- Top 2 : 8 Chỉ Số Tự Chọn Thêm Vào Trang Bị Đang Mặc ( Chỉ Số X2 Chỉ Số Top Tặng Hoa ).\n"
                                        + "- Top 3 : 5 Chỉ Số Tự Chọn Thêm Vào Trang Bị Đang Mặc ( Chỉ Số X2 Chỉ Số Top Tặng Hoa ).\n"
                                        + "- Top 4 tới top 10 : 1 Pet Bóng Ma Hoặc Pet Yêu Tinh Max Chỉ Số Vĩnh Viễn.\n"
                                );
                                break;
                        }
                        break;

                    default: {
                        break;
                    }
                }
                break;
            }
            case EventName.HALLOWEEN: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        Service.sendInputDialog(p, (short) -20, "Nhập số lượng");
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) -19, "Nhập số lượng");
                        break;
                    case 2:
                        Server.manager.sendTB(p, "Top Điểm Sự Kiện", Rank.getStringBXH(4)
                                + "\n- Điểm Của Bạn : " + p.c.eventPoint);
                        break;
                    case 3:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "  - Kẹo Táo : Quả Táo + Mật Ong + 20 Lượng.\n"
                                + "- Hộp Ma Qủy  : Xương Thú + Tàn Linh + 50 Lượng ( 1 Điểm Đua Top Hộp Ma Qủy + Tỉ Lệ Ra Đồ Hiếm Cao ).\n"
                                + "- Dùng Hộp Ma Qủy Cần Chìa Khóa Ở Ghoso Với Giá 20K XU "
                        );
                        break;
                    default: {
                        break;
                    }
                }
                break;
            }
            case EventName.DUA_HAU: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        if (p.c.level < 30){
                            Service.chatNPC(p, (short) npcid, "yêu level 30 trớ lên");
                            return;
                        }
                        Service.sendInputDialog(p, (short) -29, "Nhập số lượng");
                        break;
                    case 1:
                        if (p.c.level < 30){
                            Service.chatNPC(p, (short) npcid, "yêu level 30 trớ lên");
                            return;
                        }
                        Service.sendInputDialog(p, (short) -30, "Nhập số lượng");
                        break;
                    case 2:
                        Server.manager.sendTB(p, "Top Điểm Sự Kiện", Rank.getStringBXH(4)
                                + "\n- Điểm Của Bạn : " + p.c.eventPoint);
                        break;
                    case 3:
                        Server.manager.sendTB(p, "Hướng dẫn", "Dưa Hấu Dài [không khóa] = 10\n"
                                + "Miếng Dưa Hấu (ID:677) + 100.000 XU.\n\n"
                                + "Dưa Hấu Tròn [khóa] = 10\n"
                                + " 10 Miến Dưa Hấu (ID:677) + 50 Lượng"
                        );
                    break;
                    default: {
                        break;
                    }
                }
                break;
            }
            case EventName.VU_LAN: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        Service.sendInputDialog(p, (short) -31, "Nhập số lượng");
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) -32, "Nhập số lượng");
                        break;
                    case 2:
                        Server.manager.sendTB(p, "Top Điểm Sự Kiện", Rank.getStringBXH(4)
                                + "\n- Điểm Của Bạn : " + p.c.eventPoint);
                        break;
                    case 3:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "1 Bó Sen Trắng Không Khóa = 10 Sen Trắng + 100000 xu\n"
                                + "1 Bó Sen Hồng Khóa = 10 Sen Hồng + 1 Màu Nhuộm\n"
                                + "Màu Nhuộm Mua Tại GoSho Giá 50 Lượng"
                        );
                        break;
                    default: {
                        break;
                    }
                }
                break;
            }
            case EventName.SK_NOEL: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        Service.sendInputDialog(p, (short) -55, "Nhập số lượng");
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) -56, "Nhập số lượng");
                        break;
                    case 2:
                        Server.manager.sendTB(p, "Top Điểm Sự Kiện", Rank.getStringBXH(4)
                                + "\n- Điểm Của Bạn : " + p.c.eventPoint);
                        break;
                    case 3:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- 5 (Bơ, Kem, Đường Bột)\n"
                                + "+ 1 Chocolate = 1 Bánh Khúc Chocolate [Khóa]\n\n"
                                + "- 5 (Bơ, Kem, Đường Bột)\n"
                                + "+ 1 Dâu Tây = 1 Bánh Khúc Dâu Tây [Không Khóa]\n\n"
                                + "Dâu Tây Mua ở Goosho Với Giá 100.000 Xu\n\n"
                                + "Chocolate Mua ở Goosho Với Giá 50 Lượng\n\n"
                        );
                        break;
                    default: {
                        break;
                    }
                }
                break;
            }
            case EventName.SK_TET_NGUYEN_DAN: {
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                switch (menuId) {
                    case 0:
                        Service.sendInputDialog(p, (short) -59, "Nhập số lượng");
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) -60, "Nhập số lượng");
                        break;
                    case 2:
                        Service.sendInputDialog(p, (short) -61, "Nhập số lượng");
                        break;
                    case 3:
                        Server.manager.sendTB(p, "Top Điểm Sự Kiện", Rank.getStringBXH(4)
                                + "\n- Điểm Của Bạn : " + p.c.eventPoint);
                        break;
                    case 4:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "- 5 (Lá dong, Nếp, Đậu xanh, Lạt tre)\n"
                                + "+ 3 Thịt heo + 20 Lượng = 1 Bánh chưng [Khóa]\n\n"
                                + "- 2 (Lá dong, Nếp, Đậu xanh, Lạt tre)\n"
                                + "+ 50k xu = 1 Bánh tét [Không khóa]\n\n"
                                + "Thịt heo Mua ở Goosho Với Giá 10 Lượng\n\n"
                                + "- 5 Mảnh pháo hoa + 150 lượng + 100k xu = 1 Tràng Pháo\n\n"
                        );
                        break;
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
}
