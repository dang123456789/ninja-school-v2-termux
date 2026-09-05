package Upgrade;

import assembly.Item;
import assembly.Option;
import assembly.Player;
import io.Util;
import server.Manager;
import server.Service;
import template.ItemTemplate;

/*
 * @author thanh
 */
public class ThucTinh {
    public static int[] Percentvk = new int[]{-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 50, 45, 40, 35, 30, 25, 20, 15, 10, 5};
    public static int[] Luongvk = new int[]{-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 12000, 14000, 16000, 18000, 20000, 22000, 24000, 26000, 28000, 30000};
    public static int[] xuvk = new int[]{-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 120000000, 140000000, 160000000, 180000000, 200000000, 220000000, 240000000, 260000000, 280000000, 300000000};
    public static int[] Percent = new int[]{-1, -1, -1, -1, -1, 50, 45, 40, 35, 30, 25, 20, 15, 10, 5};
    public static int[] Luong = new int[]{-1, -1, -1, -1, -1, 6000, 7000, 8000, 9000, 10000, 11000, 12000, 13000, 14000, 15000};
    public static int[] xu = new int[] {-1, -1, -1, -1, -1, 60000000, 70000000, 80000000, 90000000, 100000000, 110000000, 120000000, 130000000, 140000000, 150000000};
    public static void ThucTinh(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                switch (b3) {
                    case 0: {
                        byte i;
                        Item Non = p.c.get().ItemBody[0];
                        if (Non == null) {
                            p.sendAddchatYellow("Hãy Mặc Nón Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Non.options.size(); ++i) {
                            if (Non.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Nón Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Non.options.add(new Option(58, 5));
                        Non.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[0].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 0);
                        p.c.addItemBag(true, Non);
                        break;
                    }
                    case 1: {
                        int i;
                        Item VuKhi = p.c.get().ItemBody[1];
                        if (VuKhi == null) {
                            p.sendAddchatYellow("Hãy Mặc Vũ Khí Vào Trước ");
                            return;
                        }
                        if (VuKhi.upgrade < 16) {
                            p.sendAddchatYellow("Vũ Khí +16 Mới Có Thể Thức Tỉnh");
                            return;
                        }
                        if (p.c.xu < 80000000) {
                            p.sendAddchatYellow("Cần 80.000.000 Xu");
                            return;
                        }
                        if (p.luong < 80000) {
                            p.sendAddchatYellow("Cần 80000 Lượng");
                            return;
                        }
                        for (i = 0; i < VuKhi.options.size(); ++i) {
                            if (VuKhi.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Vũ Khí Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        VuKhi.options.add(new Option(58, 10));
                        VuKhi.options.add(new Option(57, 100));
                        VuKhi.options.add(new Option(113, 2000));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-80000000);
                        p.upluongMessage(-80000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[1].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 1);
                        p.c.addItemBag(true, VuKhi);
                        break;
                    }
                    case 2: {
                        byte i;
                        Item Ao = p.c.get().ItemBody[2];
                        if (Ao == null) {
                            p.sendAddchatYellow("Hãy Mặc Áo Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Ao.options.size(); ++i) {
                            if (Ao.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Áo Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Ao.options.add(new Option(58, 5));
                        Ao.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[2].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 2);
                        p.c.addItemBag(true, Ao);
                        break;
                    }
                    case 3: {
                        byte i;
                        Item DayChuyen = p.c.get().ItemBody[3];
                        if (DayChuyen == null) {
                            p.sendAddchatYellow("Hãy Mặc Dây Chuyền Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < DayChuyen.options.size(); ++i) {
                            if (DayChuyen.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Dây Chuyền Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        DayChuyen.options.add(new Option(58, 5));
                        DayChuyen.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[3].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 3);
                        p.c.addItemBag(true, DayChuyen);
                        break;
                    }
                    case 4: {
                        byte i;
                        Item Gang = p.c.get().ItemBody[4];
                        if (Gang == null) {
                            p.sendAddchatYellow("Hãy Mặc Găng Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Gang.options.size(); ++i) {
                            if (Gang.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Găng Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Gang.options.add(new Option(58, 5));
                        Gang.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[4].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 4);
                        p.c.addItemBag(true, Gang);
                        break;
                    }
                    case 5: {
                        byte i;
                        Item Nhan = p.c.get().ItemBody[5];
                        if (Nhan == null) {
                            p.sendAddchatYellow("Hãy Mặc Nhẫn Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Nhan.options.size(); ++i) {
                            if (Nhan.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Nhẫn Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Nhan.options.add(new Option(58, 5));
                        Nhan.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[5].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 5);
                        p.c.addItemBag(true, Nhan);
                        break;
                    }
                    case 6: {
                        byte i;
                        Item Quan = p.c.get().ItemBody[6];
                        if (Quan == null) {
                            p.sendAddchatYellow("Hãy Mặc Quần Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Quan.options.size(); ++i) {
                            if (Quan.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Quần Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Quan.options.add(new Option(58, 5));
                        Quan.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[6].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 6);
                        p.c.addItemBag(true, Quan);
                        break;
                    }
                    case 7: {
                        byte i;
                        Item Boi = p.c.get().ItemBody[7];
                        if (Boi == null) {
                            p.sendAddchatYellow("Hãy Mặc Bội Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Boi.options.size(); ++i) {
                            if (Boi.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Bội Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Boi.options.add(new Option(58, 5));
                        Boi.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[7].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 7);
                        p.c.addItemBag(true, Boi);
                        break;
                    }
                    case 8: {
                        byte i;
                        Item Giay = p.c.get().ItemBody[8];
                        if (Giay == null) {
                            p.sendAddchatYellow("Hãy Mặc Giày Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Giay.options.size(); ++i) {
                            if (Giay.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Giày Đã Được Thức Tĩnh.");
                                return;
                            }
                        }
                        Giay.options.add(new Option(58, 5));
                        Giay.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[8].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 8);
                        p.c.addItemBag(true, Giay);
                        break;
                    }
                    case 9: {
                        byte i;
                        Item Bua = p.c.get().ItemBody[9];
                        if (Bua == null) {
                            p.sendAddchatYellow("Hãy Mặc Bùa Vào Trước ");
                            return;
                        }
                        if (p.c.xu < 40000000) {
                            p.sendAddchatYellow("Cần 40.000.000 Xu");
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
                        for (i = 0; i < Bua.options.size(); ++i) {
                            if (Bua.options.get(i).id == 58) {
                                Service.chatNPC(p, (short) npcid, " Bùa Đã Được Thức Tỉnh.");
                                return;
                            }
                        }
                        Bua.options.add(new Option(58, 5));
                        Bua.options.add(new Option(57, 50));
                        p.sendAddchatYellow("Thức Tỉnh Thành Công");
                        p.c.upxuMessage(-40000000);
                        p.upluongMessage(-40000);
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[9].id);
                        Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                                + p.c.name + " Vừa Thức Tỉnh Thành Công Trang Bị " 
                                + data.name + " Sức Mạnh Lên Một Tầm Cao Mới"
                        );
                        p.c.removeItemBody((byte) 9);
                        p.c.addItemBag(true, Bua);
                        break;
                    }
                    case 10: {
                        Service.chatNPC(p, (short) npcid, "ChưaCóHướngDẫnĐâu!");
                        break;
                    }
                    default:
                        Service.chatNPC(p, (short) npcid, "Đang Cập Nhật.");
                        break;
                }
                break;
            }
            case 1: {
                switch (b3) {
                    case 0: {
                        byte i;
                        Item Non = p.c.get().ItemBody[0];
                        if (Non == null) {
                            p.sendAddchatYellow("Hãy Mặc Nón Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Non.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Non.options.size(); ++i) {
                            if (Non.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Nón Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Non.options.size(); ++i) {
                            Option option = Non.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Nón Đá Đã Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Non.upgrade < 16) {
                            p.sendAddchatYellow("Nón +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[0].id);
                        Service.startYesNoDlg(p, (byte) -1_0, "Bạn có muốn nâng Thức Tỉnh của Nón " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 1: {
                        byte i;
                        Item VuKhi = p.c.get().ItemBody[1];
                        if (VuKhi == null) {
                            p.sendAddchatYellow("Hãy Mặc Vũ Khí Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : VuKhi.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < VuKhi.options.size(); ++i) {
                            if (VuKhi.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Vũ khí Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam20 = false;
                        for (i = 0; i < VuKhi.options.size(); ++i) {
                            Option option = VuKhi.options.get(i);
                            if (option.id == 58 && option.param == 20) {
                                hasOption58WithParam20 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam20) {
                            p.sendAddchatYellow("Vũ Khí Đã Đật Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xuvk[param]) {
                            p.sendAddchatYellow("Cần " + xuvk[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luongvk[param]) {
                            p.sendAddchatYellow("Cần " + Luongvk[param] + " Lượng");
                            return;
                        }
                        if (VuKhi.upgrade < 16) {
                            p.sendAddchatYellow("Vũ khí +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[1].id);
                        Service.startYesNoDlg(p, (byte) -1_1, "Bạn có muốn nâng Thức Tỉnh của vũ khí " + data.name + " lên cấp "+ (param - 9)
                                + " Với: " + ThucTinh.Luongvk[param] + " lượng và " + ThucTinh.xuvk[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percentvk[param] + "% không?"
                        );     
                        break;
                    }
                    case 2: {
                        byte i;
                        Item AO = p.c.get().ItemBody[2];
                        if (AO == null) {
                            p.sendAddchatYellow("Hãy Mặc Áo Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : AO.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < AO.options.size(); ++i) {
                            if (AO.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Áo Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < AO.options.size(); ++i) {
                            Option option = AO.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Áo Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (AO.upgrade < 16) {
                            p.sendAddchatYellow("Áo +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[2].id);
                        Service.startYesNoDlg(p, (byte) -1_2, "Bạn có muốn nâng Thức Tỉnh của Áo " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 3: {
                        byte i;
                        Item DayChuyen = p.c.get().ItemBody[3];
                        if (DayChuyen == null) {
                            p.sendAddchatYellow("Hãy Mặc Dây Chuyền Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : DayChuyen.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < DayChuyen.options.size(); ++i) {
                            if (DayChuyen.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Dây Chuyền Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < DayChuyen.options.size(); ++i) {
                            Option option = DayChuyen.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Dây Chuyền Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (DayChuyen.upgrade < 16) {
                            p.sendAddchatYellow("Dây Chuyền +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[3].id);
                        Service.startYesNoDlg(p, (byte) -1_3, "Bạn có muốn nâng Thức Tỉnh của Dây Chuyền " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 4: {
                        byte i;
                        Item Gang = p.c.get().ItemBody[4];
                        if (Gang == null) {
                            p.sendAddchatYellow("Hãy Mặc Găng Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Gang.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Gang.options.size(); ++i) {
                            if (Gang.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Găng Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Gang.options.size(); ++i) {
                            Option option = Gang.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Găng Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Gang.upgrade < 16) {
                            p.sendAddchatYellow("Găng +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[4].id);
                        Service.startYesNoDlg(p, (byte) -1_4, "Bạn có muốn nâng Thức Tỉnh của Găng " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 5: {
                        byte i;
                        Item Nhan = p.c.get().ItemBody[5];
                        if (Nhan == null) {
                            p.sendAddchatYellow("Hãy Mặc Nhẫn Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Nhan.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Nhan.options.size(); ++i) {
                            if (Nhan.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Nhẫn Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Nhan.options.size(); ++i) {
                            Option option = Nhan.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Nhẫn Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Nhan.upgrade < 16) {
                            p.sendAddchatYellow("Nhẫn +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[5].id);
                        Service.startYesNoDlg(p, (byte) -1_5, "Bạn có muốn nâng Thức Tỉnh của Nhẫn " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 6: {
                        byte i;
                        Item Quan = p.c.get().ItemBody[6];
                        if (Quan == null) {
                            p.sendAddchatYellow("Hãy Mặc Quần Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Quan.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Quan.options.size(); ++i) {
                            if (Quan.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Quần Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Quan.options.size(); ++i) {
                            Option option = Quan.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Quần Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Quan.upgrade < 16) {
                            p.sendAddchatYellow("Nhẫn +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[6].id);
                        Service.startYesNoDlg(p, (byte) -1_6, "Bạn có muốn nâng Thức Tỉnh của Quần " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 7: {
                        byte i;
                        Item Boi = p.c.get().ItemBody[7];
                        if (Boi == null) {
                            p.sendAddchatYellow("Hãy Mặc Bội Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Boi.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Boi.options.size(); ++i) {
                            if (Boi.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Bội Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Boi.options.size(); ++i) {
                            Option option = Boi.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Bội Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Boi.upgrade < 16) {
                            p.sendAddchatYellow("Bội +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[7].id);
                        Service.startYesNoDlg(p, (byte) -1_7, "Bạn có muốn nâng Thức Tỉnh của Bội " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 8: {
                        byte i;
                        Item Giay = p.c.get().ItemBody[8];
                        if (Giay == null) {
                            p.sendAddchatYellow("Hãy Mặc Giày Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Giay.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Giay.options.size(); ++i) {
                            if (Giay.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Giày Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Giay.options.size(); ++i) {
                            Option option = Giay.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Giày Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Giay.upgrade < 16) {
                            p.sendAddchatYellow("Giày +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[8].id);
                        Service.startYesNoDlg(p, (byte) -1_8, "Bạn có muốn nâng Thức Tỉnh của Giày " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 9: {
                        byte i;
                        Item Bua = p.c.get().ItemBody[9];
                        if (Bua == null) {
                            p.sendAddchatYellow("Hãy Mặc Bùa Vào Trước ");
                            return;
                        }
                        int param = 5;
                        for (Option option : Bua.options) {
                            if (option.id == 58) {
                                param = option.param;
                                break;
                            }
                        }
                        boolean containsOption58 = false;
                        for (i = 0; i < Bua.options.size(); ++i) {
                            if (Bua.options.get(i).id == 58) {
                                containsOption58 = true;
                                break;
                            }
                        }
                        if (!containsOption58) {
                            p.sendAddchatYellow("Bùa Phải Được Thức Tĩnh Mới Có Thể Nâng.");
                            return;
                        }
                        boolean hasOption58WithParam15 = false;
                        for (i = 0; i < Bua.options.size(); ++i) {
                            Option option = Bua.options.get(i);
                            if (option.id == 58 && option.param == 15) {
                                hasOption58WithParam15 = true;
                                break;
                            }
                        }
                        if (hasOption58WithParam15) {
                            p.sendAddchatYellow("Bùa Đã Đạt Cấp Thức Tĩnh Tối Đa.");
                            return;
                        }
                        if (p.c.xu < xu[param]) {
                            p.sendAddchatYellow("Cần " + xu[param] + " Xu");
                            return;
                        }
                        if (p.luong < Luong[param]) {
                            p.sendAddchatYellow("Cần " + Luong[param] + " Lượng");
                            return;
                        }
                        if (Bua.upgrade < 16) {
                            p.sendAddchatYellow("Bùa +16 Mới Có Thể Nâng Thức Tỉnh");
                            return;
                        }
                        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[9].id);
                        Service.startYesNoDlg(p, (byte) -1_9, "Bạn có muốn nâng Thức Tỉnh của Bùa " + data.name + " lên cấp "+ (param - 4)
                                + " Với: " + ThucTinh.Luong[param] + " lượng và " + ThucTinh.xu[param] + " xu"
                                + " Với Tỉ Lệ Thành Công : " + ThucTinh.Percent[param] + "% không?"
                        );
                        break;
                    }
                    case 10: {
                        Service.chatNPC(p, (short) npcid, "Chưa Có Hướng Dẫn Đâu!");
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
    public static void NangThucTinhNon(Player p) {
        Item Non = p.c.get().ItemBody[0];
        int param = 5;
        for (Option option : Non.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Non.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Non.setLock(true);
            p.c.addItemBag(false, Non);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[0].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 0);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhVuKhi(Player p) {
        Item VuKhi = p.c.get().ItemBody[1];
        int param = 10;
        for (Option option : VuKhi.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percentvk[param] >= Util.nextInt(100)) {
            for (Option option : VuKhi.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
                if (option.id == 113) {
                    option.param += 100;
                }
            }
        VuKhi.setLock(true);
        p.c.addItemBag(false, VuKhi);
        Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
        ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[1].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -9) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 1);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xuvk[param]);
        p.upluongMessage(-Luongvk[param]);
    }
    public static void NangThucTinhAo(Player p) {
        Item AO = p.c.get().ItemBody[2];
        int param = 5;
        for (Option option : AO.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : AO.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            AO.setLock(true);
            p.c.addItemBag(false, AO);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[2].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 2);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhDayChuyen(Player p) {
        Item DayChuyen = p.c.get().ItemBody[3];
        int param = 5;
        for (Option option : DayChuyen.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : DayChuyen.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            DayChuyen.setLock(true);
            p.c.addItemBag(false, DayChuyen);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[3].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 3);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhGang(Player p) {
        Item Gang = p.c.get().ItemBody[4];
        int param = 5;
        for (Option option : Gang.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Gang.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Gang.setLock(true);
            p.c.addItemBag(false, Gang);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[4].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 4);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhNhan(Player p) {
        Item Nhan = p.c.get().ItemBody[5];
        int param = 5;
        for (Option option : Nhan.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Nhan.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Nhan.setLock(true);
            p.c.addItemBag(false, Nhan);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[5].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 5);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhQuan(Player p) {
        Item Quan = p.c.get().ItemBody[6];
        int param = 5;
        for (Option option : Quan.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Quan.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Quan.setLock(true);
            p.c.addItemBag(false, Quan);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[6].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 6);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhBoi(Player p) {
        Item Boi = p.c.get().ItemBody[7];
        int param = 5;
        for (Option option : Boi.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Boi.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Boi.setLock(true);
            p.c.addItemBag(false, Boi);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[7].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 7);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhGiay(Player p) {
        Item Giay = p.c.get().ItemBody[8];
        int param = 5;
        for (Option option : Giay.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Giay.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Giay.setLock(true);
            p.c.addItemBag(false, Giay);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[8].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 8);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
    public static void NangThucTinhBua(Player p) {
        Item Bua = p.c.get().ItemBody[9];
        int param = 5;
        for (Option option : Bua.options) {
            if (option.id == 58) {
                param = option.param;
                break;
            }
        }
        if (ThucTinh.Percent[param] >= Util.nextInt(100)) {
            for (Option option : Bua.options) {
                if (option.id == 58) {
                    option.param += 1;
                }
                if (option.id == 57) {
                    option.param += 10;
                }
            }
            Bua.setLock(true);
            p.c.addItemBag(false, Bua);
            Service.chatNPC(p, (short) 49, "Nâng Cấp Thành Công");
            ItemTemplate data = ItemTemplate.ItemTemplateId(p.c.get().ItemBody[9].id);
            Manager.serverChat("Thông Báo", " Chúc Mừng Người Chơi "
                + p.c.name + " Vừa Nâng Trang Bị " 
                + data.name + " Lên Thức Tỉnh " 
                + (param -4) + " Thành Công Sức Mạnh Lại Tiến Thêm Mốt Bước"
            );
            p.c.removeItemBody((byte) 9);
        } else {
        Service.chatNPC(p, (short) 49, " Nâng Cấp Thất Bại !");
        }
        p.c.upxuMessage(-xu[param]);
        p.upluongMessage(-Luong[param]);
    }
}
