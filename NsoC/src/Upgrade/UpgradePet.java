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
public class UpgradePet {
    
    public static int[] Luong = new int[]{100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000}; // lượng
    public static int[] xu = new int[]{1000000, 2000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000, 3000000};
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

    public static void MenuUpgradePet(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
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
                    b = "Chỉ số Cùi thì ta làm lại . Dừng lại là Thất Bại rồi !";
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
                if (p.luong < Luong[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ Lượng Để Nâng Cấp pet ưng long");
                    return;
                }
                if (p.c.xu < xu[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Bạn Không Đủ xu Để Nâng Cấp pet ưng long");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[10].id);
                Service.startYesNoDlg(p, (byte) 10, "Bạn có muốn nâng cấp " + data.name + " cấp " + (Item.upgrade + 1)
                        + " với " + Luong[Item.upgrade] + " Lượng  vs " + xu[Item.upgrade] + " xu Với Tỉ Lệ Thành Công : "
                        + UpgradePet.Percent[Item.upgrade] + "% không?"
                );
                break;
            case 2: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện Pet Ưng Long cần mang lên người Pet Ưng Long và + 1000 Lượng  \n"
                        + "- Luyện Pet Ưng Long sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp Pet Ưng Long . Các dòng chỉ số Pet Ưng Long của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất 1 ít ngân Lượng \n"
                );
                break;
            }
        }
    }

    public static void UpgradePet(Player p) {
        Item it = p.c.get().ItemBody[10];
        LichSu.LichSuLuong(p.c.name, p.luong, p.luong - Luong[it.upgrade], " Nâng pet ", -Luong[it.upgrade]);
        p.upluongMessage(-Luong[it.upgrade]);
        p.c.upxuMessage(-xu[it.upgrade]);
        if (UpgradePet.Percent[it.getUpgrade()] >= Util.nextInt(150)) {
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
            p.c.addItemBag(false, it);
            Service.chatNPC(p, (short) 61, "Nâng Cấp Thành Công");
            p.c.removeItemBody((byte) 10);
        } else {
            Service.chatNPC(p, (short) 61, " Nâng Cấp Thất Bại !");
        }
    }
}
