package ChanLe;

import java.util.ArrayList;

public class SoiCaucl {
    
    public String ketquacl;

    public String soramdomcl;

    public static ArrayList<SoiCaucl> soicau = new ArrayList<>();

    public SoiCaucl(String name, String tong) {
        this.ketquacl = name;
        this.soramdomcl = tong;
    }

    public static void clear() {
        soicau = new ArrayList<>();
    }
}
