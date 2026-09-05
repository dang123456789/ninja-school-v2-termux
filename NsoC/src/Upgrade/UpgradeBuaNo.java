package Upgrade;

import History.LichSu;
import Item.ItemName;
import static Menu.AnToc.Luong;
import static Upgrade.BiKip.Luong;
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
 * @author thanh
 */
public class UpgradeBuaNo {
    
    public static int[] XuBuaNo = new int[]{5000000, 10000000, 15000000, 20000000, 25000000, 30000000, 35000000, 40000000, 45000000, 50000000, 55000000, 60000000, 65000000, 75000000, 80000000, 85000000}; // xu
    public static int[] Options = new int[]{6,73,69,81,119,120,58,68}; // chi so
    public static int[] param = new int[]{
        Util.nextInt(1, 10000),
        Util.nextInt(1, 6000),
        Util.nextInt(1, 50),
        Util.nextInt(1, 500),
        Util.nextInt(1, 5000),
        Util.nextInt(1, 5000),
        Util.nextInt(1, 7),
        Util.nextInt(1, 500)};
    public static int[] Percent = new int[]{100, 90, 80, 70, 60, 55, 50, 45, 40, 35, 30, 20, 15, 10, 5, 3};

    public static void MenuUpgradeBuaNo(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        if (p.c.isNhanban) {
            Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
            return;
        }
        switch (menuId) {
            case 0: {
                if (p.c.get().level < 60) {
                    Service.chatNPC(p, (short) npcid, "Cấp 60 trở lên mới có thể nhận Bùa Nổ");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.luong < 20000) {
                    Service.chatNPC(p, (short) npcid,"Không có đủ 20.000 lượng");
                    return;
                }
                 if (p.c.xu < 30000000) {
                    Service.chatNPC(p, (short) npcid,"Không có đủ 30.000.000 xu");
                    return;
                }
                 if (p.c.quantityItemyTotal(978) < 1000) { 
                    Service.chatNPC(p, (short) npcid, "Không đủ 1.000 Vé tinh thạch");
                    return;
                 }
                Item it = ItemTemplate.itemDefault(986);
                            it.setLock(true);
                            p.c.addItemBag(true, it);
                            p.c.removeItemBags(978, 1000);
                            p.c.upxuMessage(-30000000);
                            p.upluongMessage(-20000);
                            break;
                        }
            case 1: {
                  Item Item = p.c.get().ItemBody[15];
                 if (p.c.ItemBody[15] == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có Bùa Nổ mới có thể luyện");
                    return;
                }

                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.luong < 10000) {
                    Service.chatNPC(p, (short) npcid, "Không đủ 10.000 lượng");
                    return;
                }
               
                Item it = ItemTemplate.itemDefault(986);
                int a = Util.nextInt(8);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < UpgradeBuaNo.Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(UpgradeBuaNo.Options[indexOption], (UpgradeBuaNo.param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 15);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 500, " Mở chỉ số ", -500);
                p.upluongMessage(-10000);
                String b = "";
                if (a <= 6 && a >= 8) {
                    b = "Khai mở chỉ số thành công nhận ngẫu nhiên 6-8 dòng";
                } else if (a >= 2 && a <= 5) {
                    b = "Khai mở chỉ số thành công nhận ngẫu nhiên 2-5 dòng";
                } else {
                    b = "Khai mở chỉ số thành công nhận ngẫu nhiên";
                }
                Service.chatNPC(p, (short) npcid, b);
                return;
            }
            case 2:
                Item Item = p.c.get().ItemBody[15];
                if (Item == null) {
                    Service.chatNPC(p, (short) npcid, "Phải mang bùa nổ trên mình ta mới có thể giúp ngươi");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) npcid, "Hành trang không đủ chỗ trống");
                    return;
                }
                if (p.c.ItemBody[15] == null) {
                    Service.chatNPC(p, (short) npcid, "Bạn cần có Bủa Nổ mới có thể nâng cấp");
                    return;
                }
                if (Item.getUpgrade() >= 16) {
                    Service.chatNPC(p, (short) npcid, "Đã đạt cấp tối đa");
                    return;
                }
                if (p.c.xu < XuBuaNo[Item.upgrade]) {
                    Service.chatNPC(p, (short) npcid, "Không đủ xu nâng cấp");
                    return;
                }
              
                ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.ItemBody[15].id);
                Service.startYesNoDlg(p, (byte) 10, "Bạn có muốn nâng cấp " + data.name + " Lên Cấp " + (Item.upgrade + 1)
                        + " với " + XuBuaNo[Item.upgrade] + " xu Với Tỉ Lệ Thành Công : "
                        + UpgradeBuaNo.Percent[Item.upgrade] + "% không?"
                );
                break;
            case 3: {
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Khi Luyện Pet Ưng Long cần mang lên người Pet Ưng Long và + 1000 Lượng và 200k xu  \n"
                        + "- Luyện Pet Ưng Long sẽ ra random 1 đến 8 dòng chỉ số bất kì \n"
                        + "- Chỉ số mạnh hay yếu là do nhân phẩm của bạn \n"
                        + "- Khi Nâng Cấp Pet Ưng Long con được Các dòng chỉ số Pet Ưng Long của bạn sẽ được tăng cấp và chỉ số cao hơn \n"
                        + "- Mỗi lần nâng cấp sẽ mất 1 ít ngân Lượng \n"
                );
                break;
            }
        }
    }

    public static void UpgradeBua(Player p) {
        Item it = p.c.get().ItemBody[15];
         LichSu.LichSuLuong(p.c.name, p.luong, p.luong - XuBuaNo[it.upgrade], " Nâng Cấp ", -XuBuaNo[it.upgrade]);
        p.c.upxuMessage(-XuBuaNo[it.upgrade]);
       // p.c.upxuMessage(-xu[it.upgrade]);
        if (UpgradeBuaNo.Percent[it.getUpgrade()] >= Util.nextInt(100)) {
            for (byte k = 0; k < it.options.size(); ++k) {
                Option option = it.options.get(k);
                if (option.id == 6 || option.id == 73  || option.id == 119  || option.id == 120 ) {
                    Option ops = option;
                    ops.param += 150;
                }
                 if (option.id == 58 ) {
                    Option ops = option;
                    ops.param += 5;
                }
                if (option.id == 69 || option.id == 68 || option.id == 81) {
                    Option ops = option;
                    ops.param += 10;
                                }
            }
            it.setUpgrade(it.getUpgrade() + 1);
            it.setLock(true);
            p.c.addItemBag(false, it);
            Service.chatNPC(p, (short) 61, "Nâng Cấp Thành Công");
            p.c.removeItemBody((byte) 15);
        } else {
            Service.chatNPC(p, (short) 61, " Nâng Cấp Thất Bại !");
        }
    }
}
