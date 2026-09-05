/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Upgrade;

import History.LichSu;
import assembly.Item;
import assembly.Language;
import assembly.Option;
import assembly.Player;
import io.Util;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import server.Service;
import stream.Server;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class BiKip {

    public static int[] Luong = new int[]{100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000}; // lượng
    public static int[] Options = new int[]{79, 80, 81, 82, 83, 84, 86, 87, 91, 92, 94, 95, 96, 97, 98};
    public static int[] param = new int[]{
        Util.nextInt(1, 10),
        Util.nextInt(10, 20),
        Util.nextInt(10, 50),
        Util.nextInt(500, 1000),
        Util.nextInt(500, 1000),
        Util.nextInt(10, 20),
        Util.nextInt(10, 20),
        Util.nextInt(500, 1000),
        Util.nextInt(5, 10), // max 50
        Util.nextInt(10, 20),// max 100
        Util.nextInt(10, 20),// max 100
        Util.nextInt(10, 50),
        Util.nextInt(10, 50),
        Util.nextInt(10, 50),
        Util.nextInt(1, 10)};
    public static int[] Percent = new int[]{100, 90, 80, 70, 60, 50, 45, 40, 35, 30, 25, 20, 18, 10, 5, 3};
    
    public static void MenuNhanBiKip(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
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
                            break;
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
                break;
               }
            default: {
                Service.chatNPC(p, (short) npcid, "Chức năng này đang cập nhật!");
                break;
            }
              }
    }

    public static void MenuUpgradeBiKip(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
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
                            break;
                        }
            case 1: {
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
                    b = "Chỉ số Cùi thì ta làm lại . Dừng lại là Thất Bại rồi !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
            case 2:
                Item Item = p.c.get().ItemBody[15];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Bí Kíp Lên Người Mới Có Thể Luyện Bí Kíp");
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
                if (p.luong < Luong[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp Bí Kíp");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[15].id);
                Service.startYesNoDlg(p, (byte) 15, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + Luong[Item.upgrade] + " Lượng Và " + 5 * Item.upgrade + " Đá Nâng Cấp Với Tỉ Lệ Thành Công : "
                        + BiKip.Percent[Item.upgrade] + "% không?"
                );
                break;
            case 3: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện bí kíp cần mang lên người bí kíp và + 1000 Lượng  \n"
                        + "- Luyện bí kíp sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp bí kíp . Các dòng chỉ số bí kíp của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất 1 ít ngân Lượng và đá nâng cấp \n"
                );
                break;
            }
        }
    }

    public static void UpgradeBiKip(Player p) {
        Item it = p.c.get().ItemBody[15];
        LichSu.LichSuLuong(p.c.name, p.luong, p.luong - Luong[it.upgrade], " Nâng Bí Kíp ", -Luong[it.upgrade]);
        p.upluongMessage(-Luong[it.upgrade]);
        p.c.removeItemBags(837, 5 * it.upgrade);
        if (BiKip.Percent[it.getUpgrade()] >= Util.nextInt(150)) {
            for (byte k = 0; k < it.options.size(); ++k) {
                Option option = it.options.get(k);
                if (option.id == 79 || option.id == 98) {
                    Option ops = option;
                    ops.param += 1;
                }
                if (option.id == 80 || option.id == 84 || option.id == 86 || option.id == 92 || option.id == 94 || option.id == 94) {
                    Option ops = option;
                    ops.param += 10;
                }
                if (option.id == 81) {
                    Option ops = option;
                    ops.param += (int) 12.5;
                }
                if (option.id == 82 || option.id == 83 || option.id == 87) {
                    Option ops = option;
                    ops.param += 500;
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
            Service.chatNPC(p, (short) 45, "Nâng Cấp Thành Công");
            p.c.removeItemBody((byte) 15);
        } else {
            Service.chatNPC(p, (short) 45, " Nâng Cấp Thất Bại !");
        }
    }
}
