package Upgrade;

import History.LichSu;
//import static Item.ItemName.NHAN_THUAT_GIA_TOC_CAP_5;
import static Item.ItemName.NHAN_THUAT_GIA_TOC_CAP_5;
//import static Item.ItemName.UNG_LONG;
//import static Upgrade.UngLong.Luong;
import assembly.Item;
import assembly.Option;
import assembly.Player;
//import io.Message;
import io.Util;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import server.Manager;
import server.Service;
import stream.Server;
import template.ItemTemplate;

public class UpgradeNhanThuatGiaToc {

    public static int[] Luong = new int[]{1000, 1500, 1550, 1600, 1650, 1700, 1750, 1800, 1850, 1900, 1950, 2000, 2500, 3000, 3500, 4000}; // lượng
    public static int[] Xu = new int[]{1000000, 1100000, 1200000, 1300000, 1400000, 1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2500000, 3000000, 3500000, 4000000, 5000000}; // xu
    public static int[] Options = new int[]{79, 80, 81, 82, 83, 84, 86, 87, 91, 92, 94, 95, 96, 97, 98};
    public static int[] param = new int[]{
        Util.nextInt(1, 20),
        Util.nextInt(20, 40),
        Util.nextInt(20, 100),
        Util.nextInt(1000, 5000),
        Util.nextInt(1000, 5000),
        Util.nextInt(10, 30),
        Util.nextInt(20, 30),
        Util.nextInt(1000, 5000),
        Util.nextInt(5, 20), // max 50
        Util.nextInt(10, 50),// max 100
        Util.nextInt(10, 50),// max 100
        Util.nextInt(10, 50),
        Util.nextInt(10, 100),
        Util.nextInt(500, 100),
        Util.nextInt(10, 20)};
    public static int[] Tile = new int[]{100, 90, 80, 70, 60, 50, 40, 20, 15, 12, 11, 10, 8, 5, 3, 1}; // tỉ lệ
//    public static void UpgradeNhanThuatGiaToc(Player p, Item item, int type) throws IOException {
//        if (type == 1 && p.luong < UpgradeNhanThuatGiaToc.Luong[item.upgrade]) {
//            p.conn.sendMessageLog("Không Đủ Lượng");
//            return;
//        }
//        UpgradeNhanThuatGiaToc.UpgradeNhanThuatGiaTocOptions(p, item, type);
//        Message m = new Message(13);
//        m.writer().writeInt(p.c.xu);//xu
//        m.writer().writeInt(p.c.yen);//yen
//        m.writer().writeInt(p.luong);//luong
//        m.writer().flush();
//        p.conn.sendMessage(m);
//        m.cleanup();
//    }
    private static int [] Percent = new int[]{100, 90, 80, 70, 60, 50, 40, 20, 15, 12, 11, 10, 8, 5, 3, 1}; // tỉ lệ

    private static void Menunhanntgt(Player p, byte npcid, byte menuId, byte b3) throws IOException  {
        switch (menuId) {
            case 0: {
                if (p.c.get().nclass == 0) {
                    Service.chatNPC(p, (short) npcid, "Hãy Nhập Học Để Có Thể Luyện NTGT.");
                    return;
                }
                if (p.c.get().level < 10) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 10 Mới Có Thể Make NTGT.");
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
               /* if (p.c.quantityItemyTotal(682) < 10 ) {
                                Service.chatNPC(p, (short) npcid,"Bạn không đủ đá tái tạo.");
                                return;
                            }*/
                Item it = ItemTemplate.itemDefault(NHAN_THUAT_GIA_TOC_CAP_5);
                            it.setLock(true);
                            p.c.addItemBag(true, it);
                          //  p.c.removeItemBags(682, 10);
                            p.upluongMessage(-5000L);
                            break;
                        }
            case 1: {
                if (p.c.ItemBody[13] == null) {
                    Service.chatNPC(p, (short) npcid,"Bạn phải đeo NTGT mới có thể xóa được nhé");
                    return;
                }
                if (p.luong < 500) {
                    Service.chatNPC(p, (short) npcid,"Bạn không có đủ 500 lượng");
                    return;
                }    
                p.c.removeItemBody((byte) 13);
                p.upluongMessage(-500L);
                break;
               }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
              }
    }
    public static void MenuUpgradeNTGT(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
             case 0: {
                Server.manager.sendTB(p,
                        "TITLE",
                        "- VÀO GIA TỘC ĐI , ĐI ĐƯỜNG TẮT BÚ CẶC À !!!!!!!!!!! \n"
                        
                );
                break;
            }
            case 1: {
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
                if (p.luong < 1000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ 1000 Lượng Để Luyện NTGT");
                    return;
                }
                Item it = ItemTemplate.itemDefault(NHAN_THUAT_GIA_TOC_CAP_5);
                int a = Util.nextInt(1, 8);
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
                    b = "Chỉ số Cùi thì ta làm lại . Dừng lại là Thất Bại rồi !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
            case 2:
                Item Item = p.c.get().ItemBody[13];
                
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo NTGT Lên Người Mới Có Thể Luyện NTGT");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (Item.getUpgrade() >= 16) {
                    Service.chatNPC(p, (short) npcid, "NTGT đã đạt cấp tối đa");
                    return;
                }
                if (p.c.quantityItemyTotal(840) < 5 * Item.upgrade) {
                    ItemTemplate data = ItemTemplate.ItemTemplateId(840);
                    Service.chatNPC(p, (short) npcid, "Bạn không đủ " + 5 * Item.upgrade + " viên " + data.name + " để nâng cấp");
                    return;
                }
if (p.luong < Luong[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp NTGT");
                    return;
                }
                 if (p.c.xu < Xu[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Xu Để Nâng Cấp NTGT");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[13].id);
                Service.startYesNoDlg(p, (byte) 13, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + Luong[Item.upgrade] + " Lượng Và "+ Xu[Item.upgrade] + " Xu Và " + 5 * Item.upgrade + " Đá Nâng Cấp Với Tỉ Lệ Thành Công : "
                        + UpgradeNhanThuatGiaToc.Tile[Item.upgrade] + "% không?"
                );
                break;
            case 3: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện NTGT cần mang lên người NTGT và + 1000 Lượng  \n"
                        + "- Luyện NTGT sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp NTGT . Các dòng chỉ số NTGT của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất 1 ít ngân Lượng và đá nâng cấp \n"
                );
                break;
            }
        }
    }

    public static void UpgradeNTGT(Player p) {
        Item it = p.c.get().ItemBody[13];
        LichSu.LichSuLuong(p.c.name, p.luong, p.luong - Luong[it.upgrade], " Nâng NTGT ", -Luong[it.upgrade]);
        LichSu.LichSuLuong(p.c.name, p.c.xu, p.c.xu - Xu[it.upgrade], " Nâng NTGT ", -Xu[it.upgrade]);
        p.upluongMessage(-Luong[it.upgrade]);
        p.c.upxuMessage(-Xu[it.upgrade]);
        p.c.removeItemBags(840, 5 * it.upgrade);
        if (UpgradeNhanThuatGiaToc.Percent[it.getUpgrade()] >= Util.nextInt(150)) {
            for (byte k = 0; k < it.options.size(); ++k) {
                Option option = it.options.get(k);
                if (option.id == 79 || option.id == 98) {
                    Option ops = option;
                    ops.param += 1;
                }
                if (option.id == 80 || option.id == 84 || option.id == 86 || option.id == 92 ) {
                    Option ops = option;
                    ops.param += 10;
                }
                if ( option.id == 94) {
                    Option ops = option;
                    ops.param += 5;
                }
                if (option.id == 81) {
                    Option ops = option;
                    ops.param += (int) 12.5;
                }
                if (option.id == 82 || option.id == 83 || option.id == 87) {
                    Option ops = option;
ops.param += 120;
                }
                if (option.id == 91) {
                    Option ops = option;
                    ops.param += (int) 2.5;
                }
                if (option.id == 95 || option.id == 96 || option.id == 97) {
                    Option ops = option;
                    ops.param += (int) 6.25;
                }
            }
            it.setUpgrade(it.getUpgrade() + 1);
            it.setLock(true);
            p.c.addItemBag(true, it);
            Service.chatNPC(p, (short) 61, "Nâng Cấp Thành Công");
            p.c.removeItemBody((byte) 13);
        } else {
            Service.chatNPC(p, (short) 61, " Nâng Cấp Thất Bại !");
        }
    }
}