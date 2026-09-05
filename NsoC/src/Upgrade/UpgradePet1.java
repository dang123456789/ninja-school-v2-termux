package Upgrade;

import History.LichSu;
import Item.ItemName;
import assembly.Item;
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
 * @author thanh
 */
public class UpgradePet1 {
    
    public static int[] Luong = new int[]{100000, 200000, 300000, 400000, 500000, 600000, 600000, 600000, 700000, 700000, 750000, 800000, 800000, 800000, 800000, 1000000}; // lượng
//    public static int[] xu = new int[]{1000000, 2000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000};
    public static int[] Options = new int[]{79, 80, 81, 82, 83, 84, 86, 87, 91, 92, 94, 95, 96, 97};
    public static int[] param = new int[]{
        Util.nextInt(11, 301),
        Util.nextInt(100, 500),
        Util.nextInt(200, 700),
        Util.nextInt(10000, 20000),
        Util.nextInt(10000, 20000),
        Util.nextInt(300, 500),
        Util.nextInt(200, 400),
        Util.nextInt(10000, 20000),
        Util.nextInt(100, 200), // max 50
        Util.nextInt(150, 300),// max 100
        Util.nextInt(150, 300),// max 100
        Util.nextInt(250, 600),
        Util.nextInt(200, 600),
        Util.nextInt(200, 600)};
    public static int[] Percent = new int[]{30, 20, 20, 20, 20, 20, 20, 20, 15, 10, 15, 10, 8, 5, 3, 1};

    public static void MenuUpgradePet1(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                Item Item = p.c.ItemBody[28];
                if (p.c.get().level < 50) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Trình Độ Cấp 50 Mới Có Thể Luyện Ngọc Lưu Ly.");
                    return;
                }
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Ngọc Lưu Ly Lên Người Mới Có Thể Luyện Ngọc Lưu Ly Ưng long");
                    return;
                }
                if (p.c.get().ItemBody[28] != null && p.c.get().ItemBody[28].id != ItemName.NGOC_LUU_LY) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có Ngọc Lưu Ly mới có thể luyện");
                    return;
                }
                if (Item.upgrade >= 1) {
                    Service.chatNPC(p, (short) npcid, "Ngọc Lưu Ly Đã Được Nâng Cấp Không Thể Luyện");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 500000) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ 500000 Lượng Để Luyện Ngọc Lưu Ly");
                    return;
                }
                Item it = ItemTemplate.itemDefault(1009);
                int a = Util.nextInt(5, 8);
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
                    b = "Ngon ! Hi sinh vì Đam Mê thì chưa bao giờ là Ngu";
                } else if (a >= 2 && a <= 5) {
                    b = "Chỉ số MẠNH hay YẾU là do Nhân Phẩm của bạn !";
                } else {
                    b = "Chỉ số Cùi thì ta làm lại . Dừng lại là Thất Bại rồi !";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
            case 1:
                Item Item = p.c.get().ItemBody[28];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn Phải Đeo Ngọc Lưu Ly Lên Người Mới Có Thể Nâng");
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
                if (p.luong < Luong[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp Ngọc Lưu Ly");
                    return;
                }
//                if (p.c.xu < xu[Item.upgrade]) {
//                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ xu Để Nâng Cấp Ngọc Lưu Ly");
//                    return;
//                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[28].id);
                Service.startYesNoDlg(p, (byte) 11, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + Luong[Item.upgrade] + " Lượng  vs " + " Với Tỉ Lệ Thành Công : "
                        + UpgradePet1.Percent[Item.upgrade] + "% không?"
                );
                break;
            case 2: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện Ngọc Lưu Ly cần mang lên người và + 500k Lượng  \n"
                        + "- Luyện Ngọc Lưu Ly sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp Ngọc Lưu Ly. Các dòng chỉ số Ngọc Lưu Ly của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất ngân Lượng \n"
                );
                break;
            }
        }
    }

    public static void UpgradePet1(Player p) {
        Item it = p.c.get().ItemBody[28];
        LichSu.LichSuLuong(p.c.name, p.luong, p.luong - Luong[it.upgrade], " Nâng pet ", -Luong[it.upgrade]);
        p.upluongMessage(-Luong[it.upgrade]);
//        p.c.upxuMessage(-xu[it.upgrade]);
        if (UpgradePet1.Percent[it.getUpgrade()] >= Util.nextInt(150)) {
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
                    ops.param += (int) 15.5;
                }
                if (option.id == 82 || option.id == 83 || option.id == 87) {
                    Option ops = option;
                    ops.param += 500;
                }
                if (option.id == 91) {
                    Option ops = option;
                    ops.param += (int) 4.5;
                }
                if (option.id == 95 || option.id == 96 || option.id == 97) {
                    Option ops = option;
                    ops.param += (int) 8.25;
                }
            }
            it.setUpgrade(it.getUpgrade() + 1);
            it.setLock(true);
            p.c.addItemBag(false, it);
            Service.chatNPC(p, (short) 68, "Nâng Cấp Thành Công");
            p.c.removeItemBody((byte) 28);
        } else {
            Service.chatNPC(p, (short) 68, " Nâng Cấp Thất Bại !");
        }
    }
}
